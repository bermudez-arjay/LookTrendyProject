<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;

class ClientDelete extends Component
{
   
    public $confirmingClientDeletion = false;
    public $clientToDelete = null;
    protected $listeners = [
        'showDeleteModal' => 'confirmDelete',
       
    ];

    /**
     * Carga el cliente a eliminar y muestra el modal
     *
     * @param int $Client_ID
     */
    public function confirmDelete($Client_ID)
    {
        $this->clientToDelete = Client::find($Client_ID);
        if ($this->clientToDelete) {
            $this->confirmingClientDeletion = true;
        }
    }

   
    public function deleteClient()
    {
        if ($this->clientToDelete) {
            $this->clientToDelete->update(['Removed' => 1]);

            session()->flash('success', 'Cliente eliminado correctamente.');

            $this->confirmingClientDeletion = false;
            $this->clientToDelete = null;
            $this->dispatch('clientDeleted'); 
           $this->reset();
           
        }
    }

    public function render()
    {
        return view('livewire.clients.client-delete');
    }
}
