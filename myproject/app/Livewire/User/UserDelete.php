<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;

class UserDelete extends Component
{
    public $confirmingUserDeletion = false;
    public $userToDelete = null;

    protected $listeners = [
        'showDeleteModal' => 'confirmDelete'
    ];

    public function confirmDelete($User_ID)
    {
        $this->userToDelete = User::find($User_ID);
        $this->confirmingUserDeletion = true;
    }

    // Método para eliminar al usuario
    public function deleteUser()
    {
        if ($this->userToDelete) {
            $this->userToDelete->update(['Removed' => 1]);

            session()->flash('success', 'Usuario eliminado correctamente.');

            $this->confirmingUserDeletion = false;
            $this->userToDelete = null;
            $this->dispatch('userDeleted');
            $this->dispatch('user-notify', [
                'title' => '¡Eliminación exitosa!',
                'message' => 'Usuario eliminado correctamente.'
            ]); 
           $this->reset();
           
        }
    }
    public function render()
    {
        return view('livewire.user.user-delete');
    }
}
