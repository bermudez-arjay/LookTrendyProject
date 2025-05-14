<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;

class ClientCreate extends Component
{ public $open = false;

    // Propiedades alineadas con los nombres de las columnas
    public $Client_Identity, $Client_FirstName, $Client_LastName, $Client_Address, $Client_Phone, $Client_Email;
    

    protected $rules = [
        'Client_Identity' => 'required|string|max:15',
        'Client_FirstName' => 'required|string|max:100|min:6',
        'Client_LastName' => 'required|string|max:100',
        'Client_Email' => 'required|email|max:100|unique:clients,Client_Email',
        'Client_Address' => 'required|string|max:255',
        'Client_Phone' => 'required|string|max:8'
        
    ];   

    protected $listeners = ['openCreateClientModal' => 'openModal'];

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
            'Client_Identity',
            'Client_FirstName',
            'Client_LastName',
            'Client_Email',
            'Client_Address',
            'Client_Phone'
        ]);
    }

    public function save2()
    {
   
        $this->validate();
        
        Client::create([
            'Client_Identity' => $this->Client_Identity,
				'Client_FirstName' => $this->Client_FirstName,
				'Client_LastName' => $this->Client_LastName,
				'Client_Address' => $this->Client_Address,
				'Client_Phone' => $this->Client_Phone,
				'Client_Email' => $this->Client_Email
        ]);
      dd($this->Client_Identity);
        session()->flash('status', 'Cliente creado exitosamente.');
        $this->resetForm();
        $this->dispatch('clientCreated');
        $this->dispatch('client-notify', ['message' => 'Cliente creado exitosamente.']);
        $this->closeModal();
    }
    

    public function render()
    {
        return view('livewire.clients.client-create');
    }
}
