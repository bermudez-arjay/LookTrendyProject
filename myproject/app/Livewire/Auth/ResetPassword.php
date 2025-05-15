<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class ResetPassword extends Component
{
    public $User_Email;
    public $token, $password, $password_confirmation;

    public function mount($token)
    {
        $this->token = $token;
        $this->User_Email = request()->query('email'); 

        if (!$this->User_Email) {
            abort(404, 'Enlace de restablecimiento inválido.');
        }
    }
    
    public function resetPassword()
    {
        $this->validate([
            'User_Email' => 'required|email|exists:users,User_Email',
            'password' => 'required|min:6|confirmed',
        ]);
    
        try {
            $broker = Password::broker();
            $user = $broker->getUser(['User_Email' => $this->User_Email]);
            $status = $broker->reset([
                'User_Email' => $this->User_Email,
                'token' => $this->token,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ], function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            });
    
            if ($status == Password::PASSWORD_RESET) {
                session()->flash('message', 'Contraseña restablecida correctamente.');
                return redirect()->route('login');
            } else {
                session()->flash('error', __($status));
            }
            
        } catch (\Throwable $e) {
            session()->flash('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    }
    

    public function render()
    {
        return view('livewire.auth.reset-password')->layout('layouts.forgotPassword');
    }
}
