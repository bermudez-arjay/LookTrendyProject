<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;

class ClientCreate extends Component
{ public $open = false;

    // Propiedades alineadas con los nombres de las columnas
    public $User_FirstName, $User_LastName, $User_Address, $User_Phone, $User_Email, $User_Password, $User_Rol;
    public $roles = ['Administrador', 'Empleado'];

    protected $rules = [
        'User_FirstName' => 'required|string|max:100',
        'User_LastName' => 'required|string|max:100',
        'User_Email' => 'required|email|max:100|unique:users,User_Email',
        'User_Password' => 'required|string|min:6',
        'User_Address' => 'required|string|max:255',
        'User_Phone' => 'required|string|max:20',
        'User_Rol' => 'required|string'
    ];
    

    protected $listeners = ['openCreateUserModal' => 'openModal'];

    public function openModal()
    {
        $this->resetForm();
        $this->open = true;
    }

    public function closeModal()
    {
        $this->open = false;
    }

    public function resetForm()
    {
        $this->reset([
            'User_FirstName',
            'User_LastName',
            'User_Email',
            'User_Password',
            'User_Address',
            'User_Phone',
            'User_Rol'
        ]);
    }

    public function save()
    {
        $this->validate();
    
        User::create([
            'User_FirstName' => $this->User_FirstName,
            'User_LastName' => $this->User_LastName,
            'User_Email' => $this->User_Email,
            'User_Password' => bcrypt($this->User_Password),
            'User_Address' => $this->User_Address,
            'User_Phone' => $this->User_Phone,
            'User_Rol' => $this->User_Rol,
            'Removed' => false
        ]);
    
        $this->resetForm();
        $this->dispatch('userCreated');
        $this->closeModal();
    }
    

    public function render()
    {
        return view('livewire.user.user-create');
    }
}
