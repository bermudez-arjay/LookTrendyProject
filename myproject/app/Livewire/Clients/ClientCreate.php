<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;

class ClientCreate extends Component
{
    public $open = false;
    public $Client_Identity, $Client_FirstName, $Client_LastName, $Client_Address, $Client_Phone, $Client_Email;
    
    protected $rules = [
        'Client_Identity' => [
            'required',
            'string',
            'max:16',
            'unique:clients,Client_Identity',
            'regex:/^\d{3}-\d{6}-\d{4}[A-Za-z]$/',
        ],
        'Client_FirstName' => [
            'required',
            'string',
            'max:100',
            'min:3',
            'regex:/^[\pL\s\-]+$/u',
            'not_regex:/[\d]/'
        ],
        'Client_LastName' => [
            'required',
            'string',
            'max:100',
            'regex:/^[\pL\s\-]+$/u',
            'not_regex:/[\d]/' 
        ],
        'Client_Email' => [
            'required',
            'email:rfc,dns', 
            'max:100',
            'unique:clients,Client_Email',
            'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
        ],
        'Client_Address' => [
            'required',
            'string',
            'max:255',
            'min:10' 
        ],
        'Client_Phone' => [
            'required',
            'string',
            'size:8',
            'regex:/^[2|5|7|8]\d{7}$/',
            'unique:clients,Client_Phone' 
        ],
    ];

    public function messages()
    {
        return [
            'Client_Identity.required' => 'La cédula es obligatoria.',
            'Client_Identity.regex' => 'Formato inválido. Use: 001-123456-1234A',
            'Client_Identity.unique' => 'Esta cédula ya está registrada.',
            'Client_Identity.max' => 'La cédula no debe exceder 16 caracteres.',
            
            'Client_FirstName.required' => 'El nombre es obligatorio.',
            'Client_FirstName.min' => 'El nombre debe tener al menos 3 caracteres.',
            'Client_FirstName.max' => 'El nombre no debe exceder 100 caracteres.',
            'Client_FirstName.regex' => 'Solo letras, espacios y guiones.',
            'Client_FirstName.not_regex' => 'El nombre no puede contener números.',
            
            'Client_LastName.required' => 'El apellido es obligatorio.',
            'Client_LastName.regex' => 'Solo letras, espacios y guiones.',
            'Client_LastName.not_regex' => 'El apellido no puede contener números.',
            'Client_LastName.max' => 'El apellido no debe exceder 100 caracteres.',
            
            'Client_Email.required' => 'El correo electrónico es obligatorio.',
            'Client_Email.email' => 'Ingrese un correo electrónico válido.',
            'Client_Email.unique' => 'Este correo electrónico ya está registrado.',
            'Client_Email.regex' => 'Formato de correo inválido.',
            'Client_Email.max' => 'El correo no debe exceder 100 caracteres.',
            
            'Client_Address.required' => 'La dirección es obligatoria.',
            'Client_Address.min' => 'La dirección debe tener al menos 10 caracteres.',
            'Client_Address.max' => 'La dirección no debe exceder 255 caracteres.',
            
            'Client_Phone.required' => 'El teléfono es obligatorio.',
            'Client_Phone.regex' => 'Teléfono nicaragüense válido (8 dígitos, comenzando con 2, 5, 7 u 8).',
            'Client_Phone.size' => 'El teléfono debe tener exactamente 8 dígitos.',
            'Client_Phone.unique' => 'Este teléfono ya está registrado.',
        ];
    }

    protected $listeners = ['openCreateClientModal' => 'openModal'];

    public function openModal()
    {
        $this->resetForm();
        $this->resetValidation();
        $this->open = true;
    }

    public function closeModal()
    {
        $this->open = false;
    }

    public function resetForm()
    {
        $this->reset([
            'Client_Identity',
            'Client_FirstName',
            'Client_LastName',
            'Client_Email',
            'Client_Address',
            'Client_Phone'
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        
        // Formatear automáticamente la cédula mientras se escribe
        if ($propertyName === 'Client_Identity') {
            $value = preg_replace('/[^0-9A-Za-z]/', '', $this->Client_Identity);
            if (strlen($value) >= 3) {
                $value = substr($value, 0, 3) . '-' . substr($value, 3);
            }
            if (strlen($value) >= 10) {
                $value = substr($value, 0, 10) . '-' . substr($value, 10);
            }
            $this->Client_Identity = strtoupper(substr($value, 0, 16));
        }
        
        // Formatear automáticamente el teléfono
        if ($propertyName === 'Client_Phone') {
            $this->Client_Phone = preg_replace('/[^0-9]/', '', $this->Client_Phone);
        }
    }

    public function save()
    {
        $this->validate();
        
        try {
            $client = Client::create([
                'Client_Identity' => strtoupper($this->Client_Identity),
                'Client_FirstName' => ucwords(strtolower($this->Client_FirstName)),
                'Client_LastName' => ucwords(strtolower($this->Client_LastName)),
                'Client_Address' => $this->Client_Address,
                'Client_Phone' => $this->Client_Phone,
                'Client_Email' => strtolower($this->Client_Email),
                'Removed' => 0
            ]);
            
            $this->dispatch('clientCreated', $client->Client_ID);
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Cliente creado exitosamente.',
                'timeout' => 3000
            ]);
            $this->closeModal();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al crear el cliente: '.$e->getMessage(),
                'timeout' => 5000
            ]);
        }
    }

    public function render()
    {
        return view('livewire.clients.client-create');
    }
}