<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
class ForgotPassword extends Component
{
    public $User_Email;  
    protected $rules = [
        'User_Email' => 'required|email|exists:users,User_Email',  
    ];

    protected $messages = [
        'User_Email.exists' => 'Este correo no está registrado.',
    ];


    public function send()
    {
        $this->validate();

        $response = Password::sendResetLink(
            ['User_Email' => $this->User_Email] 
        );

        if ($response == Password::RESET_LINK_SENT) {
            session()->flash('status', 'Te hemos enviado un enlace para restablecer tu contraseña.');
        } else {
            session()->flash('error', 'Hubo un problema al enviar el enlace de restablecimiento.');
        }

        $this->reset('User_Email');
    }   
    
    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('layouts.forgotPassword');
    }
}
