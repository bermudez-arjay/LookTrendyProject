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

    // Reglas de validación actualizadas
    protected function rules()
    {
        return [
            'Supplier_Identity' => [
                'required',
                'string',
                'max:16',
                'unique:suppliers,Supplier_Identity,'.$this->supplierId.',Supplier_ID',
                'regex:/^\d{3}-\d{6}-\d{4}[A-Za-z]$/',
            ],
            'Supplier_Name' => 'required|string|max:100',
            'Supplier_Email' => [
                'required',
                'email:rfc,dns',
                'max:100',
                'unique:suppliers,Supplier_Email,'.$this->supplierId.',Supplier_ID',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'Supplier_RUC' => 'required|string|max:20',
            'Supplier_Address' => 'required|string|max:255',
            'Supplier_Phone' => 'required|string|max:8'
        ];
    }

    // Mensajes de validación consistentes con el componente de creación
    public function messages()
    {
        return [
            'Supplier_Identity.required' => 'La identificación del proveedor es obligatoria.',
            'Supplier_Identity.regex' => 'Formato inválido. Use: 001-123456-1234A',
            'Supplier_Identity.unique' => 'Esta identificación ya está registrada.',
            'Supplier_Identity.max' => 'La identificación no debe exceder 16 caracteres.',
            
            'Supplier_Name.required' => 'El nombre del proveedor es obligatorio.',
            'Supplier_Name.max' => 'El nombre no debe exceder 100 caracteres.',
            
            'Supplier_Email.required' => 'El correo electrónico es obligatorio.',
            'Supplier_Email.email' => 'Ingrese un correo electrónico válido.',
            'Supplier_Email.unique' => 'Este correo electrónico ya está registrado.',
            'Supplier_Email.regex' => 'Formato de correo inválido.',
            'Supplier_Email.max' => 'El correo no debe exceder 100 caracteres.',
            
            'Supplier_RUC.required' => 'El RUC es obligatorio.',
            'Supplier_RUC.max' => 'El RUC no debe exceder 20 caracteres.',
            
            'Supplier_Address.required' => 'La dirección es obligatoria.',
            'Supplier_Address.max' => 'La dirección no debe exceder 255 caracteres.',
            
            'Supplier_Phone.required' => 'El teléfono es obligatorio.',
            'Supplier_Phone.max' => 'El teléfono no debe exceder 8 caracteres.',
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

    // Validación en tiempo real
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        
        // Formatear automáticamente la identificación mientras se escribe
        if ($propertyName === 'Supplier_Identity') {
            $value = preg_replace('/[^0-9A-Za-z]/', '', $this->Supplier_Identity);
            if (strlen($value) >= 3) {
                $value = substr($value, 0, 3) . '-' . substr($value, 3);
            }
            if (strlen($value) >= 10) {
                $value = substr($value, 0, 10) . '-' . substr($value, 10);
            }
            $this->Supplier_Identity = strtoupper(substr($value, 0, 16));
        }
        
        // Formatear automáticamente el teléfono
        if ($propertyName === 'Supplier_Phone') {
            $this->Supplier_Phone = preg_replace('/[^0-9]/', '', $this->Supplier_Phone);
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $supplier = Supplier::findOrFail($this->supplierId);

            // Actualizar los datos del proveedor
            $supplier->update([
                'Supplier_Identity' => strtoupper($this->Supplier_Identity),
                'Supplier_Name' => $this->Supplier_Name,
                'Supplier_Email' => strtolower($this->Supplier_Email),
                'Supplier_RUC' => $this->Supplier_RUC,
                'Supplier_Address' => $this->Supplier_Address,
                'Supplier_Phone' => $this->Supplier_Phone
            ]);

            $this->dispatch('supplierUpdated');
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Proveedor actualizado exitosamente.',
                'timeout' => 3000
            ]);
            $this->closeModal();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al actualizar el proveedor: '.$e->getMessage(),
                'timeout' => 5000
            ]);
        }
    }

    public function render()
    {
        return view('livewire.supplier.supplier-edit');
    }
}