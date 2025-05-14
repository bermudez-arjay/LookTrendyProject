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
    public $keyWord = '';
    
    public function updatingSearchEmail()
    {
        $this->resetPage();
    }

    public function clearFilter()
    {
        $this->keyWord = '';
        $this->resetPage();
    }
    public function someMethod()
    {
        $this->dispatch('userCreated');
        $this->dispatch('userUpdated');
        $this->dispatch('userDeleted');
    }
    public function filteredUsers()
    {
        $keyWord = '%' . $this->keyWord . '%';

        return User::where('Removed', 0)
            ->where(function ($query) use ($keyWord) {
                $query->orWhere('User_ID', 'LIKE', $keyWord)                    
                    ->orWhere('User_FirstName', 'LIKE', $keyWord)
                    ->orWhere('User_LastName', 'LIKE', $keyWord)
                    ->orWhere('User_Address', 'LIKE', $keyWord)
                    ->orWhere('User_Phone', 'LIKE', $keyWord)
                    ->orWhere('User_Email', 'LIKE', $keyWord);
            })
            ->paginate(10);
    }
        
    public function render()
    {
        return view('livewire.user.user-component', [
            'users' => $this->filteredUsers()
        ])->layout('layouts.app');
    }
}
