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

    public $keyWord;

    public function llamarFuncion()
    {
        info("¡Función llamada desde otro componente!");
    }

    public function clearFilter()
    {
        $this->keyWord = '';
        $this->resetPage();
    }

    public function someMethod()
    {
        $this->dispatch('clientCreated');
        $this->dispatch('clientUpdated');
        $this->dispatch('clientDeleted');
    }

    public function filteredClients()
    {
        $keyWord = '%' . $this->keyWord . '%';

        return Client::where('Removed', 0)
            ->where(function ($query) use ($keyWord) {
                $query->orWhere('Client_ID', 'LIKE', $keyWord)
                      ->orWhere('Client_Identity', 'LIKE', $keyWord)
                      ->orWhere('Client_FirstName', 'LIKE', $keyWord)
                      ->orWhere('Client_LastName', 'LIKE', $keyWord)
                      ->orWhere('Client_Address', 'LIKE', $keyWord)
                      ->orWhere('Client_Phone', 'LIKE', $keyWord)
                      ->orWhere('Client_Email', 'LIKE', $keyWord);
            })
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.clients.client-component', [
            'clients' => $this->filteredClients()
        ])->layout('layouts.app');
    }
}
