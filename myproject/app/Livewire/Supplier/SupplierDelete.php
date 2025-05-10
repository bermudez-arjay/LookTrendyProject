<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use App\Models\Supplier;

class SupplierDelete extends Component
{
    public $confirmingSupplierDeletion = false;
    public $supplierToDelete = null;

    protected $listeners = [
        'showDeleteModal' => 'confirmDelete'
    ];

    public function confirmDelete($Supplier_ID)
    {
        $this->supplierToDelete = Supplier::find($Supplier_ID);
        $this->confirmingSupplierDeletion = true;
    }

    public function deleteSupplier()
    {
        if ($this->supplierToDelete) {
            // Cambiar el estado Removed a true (1) en lugar de eliminar físicamente
            $this->supplierToDelete->update(['Removed' => 1]);
            $this->confirmingSupplierDeletion = false;
            $this->dispatch('supplierDeleted'); 
        }
    }

    public function render()
    {
        return view('livewire.supplier.supplier-delete');
    }
}