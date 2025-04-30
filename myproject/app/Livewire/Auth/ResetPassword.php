<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ResetPassword extends Component
{
    public $email;

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', __('Te enviamos el enlace de restablecimiento.'));
        } else {
            $this->addError('email', __('No pudimos enviar el enlace a este correo.'));
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password')->layout('layouts.login');
    }
}
