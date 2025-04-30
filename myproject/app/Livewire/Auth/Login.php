<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
class Login extends Component
{
    public $User_Email = '';
    public $Password = '';
    public $remember = false;

    public function login(Request $request)
      {
       
        $user = User::where('User_Email', $this->User_Email)->first();
       

        if ($user && Hash::check($this->Password, $user->Password)) {

            if ($user->Removed == 1) {
                session()->flash('error', 'Tu cuenta está inactiva. Contacta al administrador.');
                return;
            }
            Auth::login($user, $this->remember);

            return redirect()->intended('/inicio');
        }

        // Si el login falla, mostrar un mensaje de error
        session()->flash('error', 'Correo o contraseña incorrectos');
    }
    public function render()
    {    
        return view('livewire.auth.login')->layout('layouts.login');
    }
}
