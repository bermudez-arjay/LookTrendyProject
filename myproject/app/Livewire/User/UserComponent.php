<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
class UserComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    protected $listeners = ['userCreated','userUpdated','userDeleted','llamarFuncion'];

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
    $this->dispatch('userCreated');
    $this->dispatch('userUpdated');
    $this->dispatch('userDeleted');
}
    public function filterByEmail()
    {
        $this->resetPage(); 
    }
    
    public function render()
    {
        $query = User::where('Active', true);

        if (!empty($this->searchEmail)) {
            $query->where('User_Email', 'like', '%' . $this->searchEmail . '%');
            $this->searchEmail = '';
        }

        $users = $query->paginate(6);

        return view('livewire.user.user-component', [
            'users' => $users
        ])->layout('layouts.app');
    }
}
