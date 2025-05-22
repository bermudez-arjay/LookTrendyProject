<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;

class UserCreate extends Component
{ 
    public $open = false;

    // Propiedades alineadas con los nombres de las columnas
    public $User_FirstName, $User_LastName, $User_Address, $User_Phone, $User_Email, $Password, $User_Role;
    public $roles = ['Administrador', 'Empleado'];

protected $rules = [
    'User_FirstName' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
    'User_LastName' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
    'User_Email' => 'required|email|max:100|unique:users,User_Email',
    'Password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
    'User_Address' => 'required|string|max:255',
    'User_Phone' => 'required|string|max:20|regex:/^[\d\s\+\-\(\)]{7,20}$/',
    'User_Role' => 'required|string|in:Administrador,Empleado'
];

protected $messages = [
    'User_FirstName.required' => 'El nombre es obligatorio.',
    'User_FirstName.regex' => 'El nombre solo puede contener letras y espacios.',
    'User_LastName.required' => 'El apellido es obligatorio.',
    'User_LastName.regex' => 'El apellido solo puede contener letras y espacios.',
    'User_Email.required' => 'El correo electrónico es obligatorio.',
    'User_Email.email' => 'Ingrese un correo electrónico válido.',
    'User_Email.unique' => 'Este correo electrónico ya está registrado.',
    'Password.required' => 'La contraseña es obligatoria.',
    'Password.min' => 'La contraseña debe tener al menos 8 caracteres.',
    'Password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial.',
    'User_Address.required' => 'La dirección es obligatoria.',
    'User_Phone.required' => 'El teléfono es obligatorio.',
    'User_Phone.regex' => 'Ingrese un número de teléfono válido.',
    'User_Role.required' => 'El rol es obligatorio.',
    'User_Role.in' => 'Seleccione un rol válido.'
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
            'Password',
            'User_Address',
            'User_Phone',
            'User_Role'
        ]);
    }

    public function save()
    {
        $this->validate();
    
        User::create([
            'User_FirstName' => $this->User_FirstName,
            'User_LastName' => $this->User_LastName,
            'User_Email' => $this->User_Email,
            'Password' => bcrypt($this->Password),
            'User_Address' => $this->User_Address,
            'User_Phone' => $this->User_Phone,
            'User_Role' => $this->User_Role,
            'Removed' => 0
        ]);
    
        $this->resetForm();
        $this->dispatch('userCreated');
        $this->dispatch('product-notify', [ 
        'title' => 'Usuario creado exitosament',
        ]);
        $this->closeModal();
    }
    

    public function render()
    {
        return view('livewire.user.user-create');
    }
}
