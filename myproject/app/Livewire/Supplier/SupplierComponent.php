<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supplier;

class SupplierComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    protected $listeners = ['supplierCreated', 'supplierUpdated', 'supplierDeleted', 'llamarFuncion'];

    public function llamarFuncion()
    {
        info("¡Función llamada desde otro componente!");
    }

    public $searchEmail = '';
    
    public function updatingSearchEmail()
    {
        $this->resetPage();
    }

    public function clearFilter()
    {
        $this->reset('searchEmail');
        $this->resetPage();
    }

    public function someMethod()
    {
        $this->dispatch('supplierCreated');
        $this->dispatch('supplierUpdated');
        $this->dispatch('supplierDeleted');
    }

    public function filterByEmail()
    {
        $this->resetPage(); 
    }
    
    public function render()
    {
        $query = Supplier::where('Removed', false);

        if (!empty($this->searchEmail)) {
            $query->where('Supplier_Email', 'like', '%' . $this->searchEmail . '%');
        }

        $suppliers = $query->paginate(6);

        return view('livewire.supplier.supplier-component', [
            'suppliers' => $suppliers
        ])->layout('layouts.app');
    }
}