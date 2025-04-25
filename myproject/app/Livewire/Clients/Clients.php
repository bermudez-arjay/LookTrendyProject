<?php

namespace App\Livewire\Clients;
use Livewire\Component;
use App\Models\Client;
use Livewire\WithPagination;

class Clients extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $searchName = '';
    
    public function updatingSearchEmail()
    {
        $this->resetPage();
    }

    public function clearFilter()
{
    $this->searchName = '';
    $this->resetPage();
}
public function someMethod()
{

}
    public function filterByEmail()
    {
        $this->resetPage(); 
    }
    
    public function render()
    {
        $query = Client::where('Removed', false);

        if (!empty($this->searchName)) {
            $query->where('User_Email', 'like', '%' . $this->searchName . '%');
            $this->searchName = '';
        }

        $clients = $query->paginate(6);
        return view('livewire.clients.clients', [
            'clients' => $clients
        ])->layout('layouts.app');
    }

}
