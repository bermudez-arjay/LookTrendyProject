<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Client\ConnectionException;

class ForgotPassword extends Component
{
    public $User_Email;
    
    protected $rules = [
        'User_Email' => 'required|email|exists:users,User_Email',
    ];

    protected $messages = [
        'User_Email.required' => 'El campo correo electrónico es obligatorio.',
        'User_Email.email' => 'Por favor ingresa una dirección de correo electrónico válida.',
        'User_Email.exists' => 'Este correo electrónico no está registrado en nuestro sistema.',
    ];

    public function send()
    {
        $this->validate();

        try {
            $response = Password::sendResetLink(
                ['User_Email' => $this->User_Email]
            );

            if ($response == Password::RESET_LINK_SENT) {
                session()->flash('status', 'Te hemos enviado un enlace para restablecer tu contraseña. Por favor revisa tu bandeja de entrada.');
            } else {
                session()->flash('error', 'Hubo un problema al enviar el enlace de restablecimiento. Por favor intenta nuevamente.');
            }
        } catch (ConnectionException $e) {
            session()->flash('error', 'No se pudo conectar al servidor. Por favor verifica tu conexión a internet e intenta nuevamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error inesperado. Por favor intenta más tarde.');
        }

        $this->reset('User_Email');
    }
    
    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('layouts.forgotPassword');
    }
}