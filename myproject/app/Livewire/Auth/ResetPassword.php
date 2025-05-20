<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Client\ConnectionException;

class ResetPassword extends Component
{
    public $User_Email;
    public $token, $password, $password_confirmation;

    protected $rules = [
        'User_Email' => 'required|email|exists:users,User_Email',
        'password' => 'required|min:6|confirmed',
    ];

    protected $messages = [
        'User_Email.required' => 'El campo correo electrónico es obligatorio.',
        'User_Email.email' => 'Por favor ingresa una dirección de correo electrónico válida.',
        'User_Email.exists' => 'Este correo electrónico no está registrado en nuestro sistema.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ];

    public function mount($token)
    {
        $this->token = $token;
        $this->User_Email = request()->query('email');

        if (!$this->User_Email) {
            session()->flash('error', 'Enlace de restablecimiento inválido: falta el correo electrónico.');
            return redirect()->route('password.request');
        }
    }
    
    public function resetPassword()
    {
        $this->validate();

        try {
            $status = Password::reset(
                [
                    'User_Email' => $this->User_Email,
                    'token' => $this->token,
                    'password' => $this->password,
                    'password_confirmation' => $this->password_confirmation,
                ],
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();
                }
            );

            if ($status == Password::PASSWORD_RESET) {
                session()->flash('success', '¡Contraseña restablecida con éxito! Ya puedes iniciar sesión con tu nueva contraseña.');
                return redirect()->route('login');
            }

            session()->flash('error', $this->getStatusMessage($status));
            
        } catch (ConnectionException $e) {
            session()->flash('error', 'No se pudo conectar al servidor. Por favor verifica tu conexión a internet e intenta nuevamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
        }
    }

    protected function getStatusMessage($status)
    {
        $messages = [
            Password::INVALID_TOKEN => 'El enlace de restablecimiento ha expirado o es inválido.',
            Password::INVALID_USER => 'No encontramos un usuario con ese correo electrónico.',
            Password::RESET_THROTTLED => 'Demasiados intentos. Por favor espera antes de intentar nuevamente.',
        ];

        return $messages[$status] ?? 'Ocurrió un error al restablecer la contraseña.';
    }
    
    public function render()
    {
        return view('livewire.auth.reset-password')->layout('layouts.forgotPassword');
    }
}