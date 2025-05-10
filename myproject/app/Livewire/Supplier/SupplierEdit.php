<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use App\Models\Supplier;

class SupplierEdit extends Component
{
    public $open = false;

    public $supplierId;
    public $Supplier_Identity, $Supplier_Name, $Supplier_Address, $Supplier_Phone, $Supplier_Email, $Supplier_RUC;

    protected $listeners = ['editSupplierByEmail' => 'loadSupplierByEmail'];

    // Reglas de validación
    protected function rules()
    {
        return [
            'Supplier_Identity' => 'required|string|max:100',
            'Supplier_Name' => 'required|string|max:100',
            'Supplier_Email' => 'required|email|max:100|unique:suppliers,Supplier_Email,' . ($this->supplierId ?? 'null') . ',Supplier_ID',
            'Supplier_RUC' => 'required|string|max:20',
            'Supplier_Address' => 'required|string|max:255',
            'Supplier_Phone' => 'required|string|max:20'
        ];
    }

    public function loadSupplierByEmail($email)
    {
        $this->open = true;
        // Buscar proveedor por email
        $supplier = Supplier::where('Supplier_Email', $email)->firstOrFail();

        // Cargar los datos del proveedor en el modal
        $this->supplierId = $supplier->Supplier_ID;
        $this->Supplier_Identity = $supplier->Supplier_Identity;
        $this->Supplier_Name = $supplier->Supplier_Name;
        $this->Supplier_Email = $supplier->Supplier_Email;
        $this->Supplier_RUC = $supplier->Supplier_RUC;
        $this->Supplier_Address = $supplier->Supplier_Address;
        $this->Supplier_Phone = $supplier->Supplier_Phone;
    }

    // Cerrar el modal y resetear los campos
    public function closeModal()
    {
        $this->resetForm();
        $this->open = false;
    }

    // Resetear los valores del formulario
    public function resetForm()
    {
        $this->reset([
            'supplierId',
            'Supplier_Identity',
            'Supplier_Name',
            'Supplier_Email',
            'Supplier_RUC',
            'Supplier_Address',
            'Supplier_Phone'
        ]);
        $this->resetErrorBag();
    }

    public function update()
    {
        $this->validate();
        $supplier = Supplier::findOrFail($this->supplierId);

        // Actualizar los datos del proveedor
        $supplier->update([
            'Supplier_Identity' => $this->Supplier_Identity,
            'Supplier_Name' => $this->Supplier_Name,
            'Supplier_Email' => $this->Supplier_Email,
            'Supplier_RUC' => $this->Supplier_RUC,
            'Supplier_Address' => $this->Supplier_Address,
            'Supplier_Phone' => $this->Supplier_Phone
        ]);

        $this->resetForm();
        $this->dispatch('supplierUpdated');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.supplier.supplier-edit');
    }
}