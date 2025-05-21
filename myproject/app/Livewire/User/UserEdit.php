<?php
namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;

class UserEdit extends Component
{
    public $open = false;

    public $userId;
    public $User_FirstName, $User_LastName, $User_Address, $User_Phone, $User_Email, $Password, $User_Role;
    public $roles = ['Administrador', 'Empleado'];
    protected $listeners = ['editUserByEmail' => 'loadUserByEmail'];

// Reglas de validación mejoradas
protected function rules()
{
    return [
        'User_FirstName' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
        'User_LastName' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
        'User_Email' => 'required|email|max:100|unique:users,User_Email,' . ($this->userId ?? 'null') . ',User_ID',
        'User_Address' => 'required|string|max:255',
        'User_Phone' => 'required|string|max:20|regex:/^[\d\s\+\-\(\)]{7,20}$/',
        'User_Role' => 'required|string|in:Administrador,Empleado',
        'Password' => 'nullable|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
    ];
}

// Mensajes de error personalizados
protected $messages = [
    'User_FirstName.required' => 'El nombre es obligatorio.',
    'User_FirstName.regex' => 'El nombre solo puede contener letras y espacios.',
    'User_LastName.required' => 'El apellido es obligatorio.',
    'User_LastName.regex' => 'El apellido solo puede contener letras y espacios.',
    'User_Email.required' => 'El correo electrónico es obligatorio.',
    'User_Email.email' => 'Ingrese un correo electrónico válido.',
    'User_Email.unique' => 'Este correo electrónico ya está registrado.',
    'User_Address.required' => 'La dirección es obligatoria.',
    'User_Phone.required' => 'El teléfono es obligatorio.',
    'User_Phone.regex' => 'Ingrese un número de teléfono válido.',
    'User_Role.required' => 'El rol es obligatorio.',
    'User_Role.in' => 'Seleccione un rol válido.',
    'Password.min' => 'La contraseña debe tener al menos 8 caracteres.',
    'Password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial.'
];

    public function loadUserByEmail($email)
    {
        $this->open = true; 
        // Buscar usuario por email
        $user = User::where('User_Email', $email)->firstOrFail();

        // Cargar los datos del usuario en el modal
        $this->userId = $user->User_ID;
        $this->User_FirstName = $user->User_FirstName;
        $this->User_LastName = $user->User_LastName;
        $this->User_Email = $user->User_Email;
        $this->User_Address = $user->User_Address;
        $this->User_Phone = $user->User_Phone;
        $this->User_Role = $user->User_Role;

        // No cargamos la contraseña por seguridad
        $this->Password = '';

    }

    // Cerrar el modal y resetear los campos
    public function closeModal()
    {
        $this->resetForm();
        $this->open = false;
    }

    // Resetear los valores del formulario
    public function resetForm()
    {
        $this->userId = null;
        $this->User_FirstName = '';
        $this->User_LastName = '';
        $this->User_Email = '';
        $this->User_Address = '';
        $this->User_Phone = '';
        $this->User_Role = '';
        $this->Password = '';
    }

    public function update()
    {
  
        $this->validate();
        $user = User::findOrFail($this->userId);

        // Actualizar los datos del usuario
        $user->update([
            'User_FirstName' => $this->User_FirstName,
            'User_LastName' => $this->User_LastName,
            'User_Email' => $this->User_Email,
            'User_Address' => $this->User_Address,
            'User_Phone' => $this->User_Phone,
            'User_Role' => $this->User_Role,
            'Password' => $this->Password
                ? bcrypt($this->Password)
                : $user->Password, 
        ]);

        $this->resetForm();
        $this->dispatch('userUpdated');
        $this->closeModal();
    }

    public function refreshUsers()
{
    $this->resetPage(); 
}
    public function render()
    {
        return view('livewire.user.user-edit');
    }
}

