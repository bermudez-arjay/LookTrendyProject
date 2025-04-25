<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;

class ClientDelete extends Component
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
            $this->userToDelete->delete();
            $this->confirmingUserDeletion = false;
            $this->dispatch('userDeleted'); 
        }
    }
    public function render()
    {
        return view('livewire.user.user-delete');
    }
}
