<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use App\Models\Credit;

class ClientDelete extends Component
{
    public $confirmingClientDeletion = false;
    public $clientToDelete = null;
    public $errorMessage = null;
    
    protected $listeners = [
        'showDeleteModal' => 'confirmDelete',
    ];

    public function confirmDelete($Client_ID)
    {
        $this->reset(['errorMessage']); 
        $this->clientToDelete = Client::find($Client_ID);
        if ($this->clientToDelete) {
            $this->confirmingClientDeletion = true;
        }
    }

    public function deleteClient()
{
    $this->reset(['errorMessage']); 
    
    if (!$this->clientToDelete) {
        return;
    }
    $blockingCredits = Credit::where('Client_ID', $this->clientToDelete->Client_ID)
        ->where(function($query) {
            $query->where('Credit_Status', 'Pendiente')
                  ->orWhere(function($q) {
                      $q->where(function($subQuery) {
                          $subQuery->whereNull('Credit_Status')
                                   ->orWhere('Credit_Status', '!=', 'Cancelado');
                      })
                      ->where('Due_Date', '<', now());
                  });
        });

    if ($blockingCredits->exists()) {
        $count = $blockingCredits->count();
        $this->errorMessage = "No se puede eliminar. El cliente tiene {$count} créditos pendientes o vencidos.";
       
        $this->dispatch('client-notify', [
            'title' => '¡Error al eliminar!',
            'message' => $this->errorMessage,
            'type' => 'error'
        ]);
        
        return; 
    }

    $this->clientToDelete->update(['Removed' => 1]);

    $this->confirmingClientDeletion = false;
    $this->dispatch('client-notify', [
        'title' => '¡Eliminación exitosa!',
        'message' => 'Cliente eliminado correctamente.'
    ]);
    
    $this->dispatch('clientDeleted');
    $this->reset(['clientToDelete', 'errorMessage']);
}
    public function render()
    {
        return view('livewire.clients.client-delete');
    }
}