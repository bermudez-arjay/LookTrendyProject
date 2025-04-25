<?php
namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;

class UserEdit extends Component
{
    public $open = false;

    public $userId;
    public $User_FirstName, $User_LastName, $User_Address, $User_Phone, $User_Email, $User_Password, $User_Rol;
    public $roles = ['Administrador', 'Empleado'];
    protected $listeners = ['editUserByEmail' => 'loadUserByEmail'];

    // Reglas de validación
    protected function rules()
    {
        return [
            'User_FirstName' => 'required|string|max:100',
            'User_LastName' => 'required|string|max:100',
            'User_Email' => 'required|email|max:100|unique:users,User_Email,' . ($this->userId ?? 'null') . ',User_ID',
            'User_Address' => 'required|string|max:255',
            'User_Phone' => 'required|string|max:20',
            'User_Rol' => 'required|string',
            'User_Password' => 'nullable|string|min:6'
        ];
    }


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
        $this->User_Rol = $user->User_Rol;

        // No cargamos la contraseña por seguridad
        $this->User_Password = '';

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
        $this->User_Rol = '';
        $this->User_Password = '';
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
            'User_Rol' => $this->User_Rol,
            'User_Password' => $this->User_Password
                ? bcrypt($this->User_Password)
                : $user->User_Password, 
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

