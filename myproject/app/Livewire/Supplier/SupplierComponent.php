<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
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
        $this->dispatch('supplierCreated');
        $this->dispatch('supplierUpdated');
        $this->dispatch('supplierDeleted');
    }

    public function filterByEmail()
    {
        $this->resetPage(); 
    }

    public function filteredSuppliers(){
        $keyWord = '%' . $this->keyWord . '%';

        return Supplier::where('Removed', 0)
            ->where(function ($query) use ($keyWord) {
                $query->where('Supplier_Identity', 'LIKE', $keyWord)
                ->orWhere('Supplier_Name', 'LIKE', $keyWord)
                ->orWhere('Supplier_Email', 'LIKE', $keyWord)
                ->orWhere('Supplier_Phone', 'LIKE', $keyWord)
                ->orWhere('Supplier_Address', 'LIKE', $keyWord);
            })
            ->paginate(10);
    }
    
    public function render()
    {
        return view('livewire.supplier.supplier-component', [
        'suppliers' => $this->filteredSuppliers()
    ])->layout('layouts.app');
    }
}