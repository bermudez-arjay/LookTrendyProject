<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use App\Models\Supplier;

class SupplierCreate extends Component
{
    public $open = false;
    public $Supplier_Identity, $Supplier_Name, $Supplier_Address, $Supplier_Phone, $Supplier_Email, $Supplier_RUC;
    
    protected $rules = [
        'Supplier_Identity' => 'required|string|max:100',
        'Supplier_Name' => 'required|string|max:100',
        'Supplier_Email' => 'required|email|max:100|unique:suppliers,Supplier_Email',
        'Supplier_RUC' => 'required|string|max:20',
        'Supplier_Address' => 'required|string|max:255',
        'Supplier_Phone' => 'required|string|max:20',
    ];

    protected $listeners = ['openCreateSupplierModal' => 'openModal'];

    public function openModal()
    {
        $this->resetForm();
        $this->open = true;
    }

    public function closeModal()
    {
        $this->open = false;
    }

    public function resetForm()
    {
        $this->reset([
            'Supplier_Identity',
            'Supplier_Name',
            'Supplier_Email',
            'Supplier_RUC',
            'Supplier_Address',
            'Supplier_Phone'
        ]);
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();
    
        Supplier::create([
            'Supplier_Identity' => $this->Supplier_Identity,
            'Supplier_Name' => $this->Supplier_Name,
            'Supplier_Email' => $this->Supplier_Email,
            'Supplier_RUC' => $this->Supplier_RUC,
            'Supplier_Address' => $this->Supplier_Address,
            'Supplier_Phone' => $this->Supplier_Phone,
            'Removed' => 0
        ]);
    
        $this->resetForm();
        $this->dispatch('supplierCreated'); // Cambiado a 'supplierCreated' para consistencia
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.supplier.supplier-create');
    }
}