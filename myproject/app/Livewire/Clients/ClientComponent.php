<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;
class ClientComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    protected $listeners = ['clientCreated','clientUpdated','clientDeleted','llamarFuncion'];

    public function llamarFuncion()
    {
        // Código que quieres ejecutar
        info("¡Función llamada desde otro componente!");
    }
    public $searchEmail = '';
    
    public function updatingSearchEmail()
    {
        $this->resetPage();
    }

    public function clearFilter()
{
    $this->searchEmail = '';
    $this->resetPage();
}
public function someMethod()
{
    $this->dispatch('clientCreated');
    $this->dispatch('clientUpdated');
    $this->dispatch('clientDeleted');
}
    public function filterByEmail()
    {
        $this->resetPage(); 
    }
    
    public function render()
    {
        $query = Client::where('Removed', false);

        if (!empty($this->searchEmail)) {
            $query->where('Client_Email', 'like', '%' . $this->searchEmail . '%');
            $this->searchEmail = '';
        }

        $clients = $query->paginate(6);

        return view('livewire.clients.client-component', [
            'clients' => $clients
        ])->layout('layouts.app');
    }
}
