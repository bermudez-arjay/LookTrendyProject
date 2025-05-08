<?php
namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;

class ClientEdit extends Component
{
    public $open = false;

  public $Client_ID;
    public  $Client_Identity, $Client_FirstName, $Client_LastName, $Client_Address, $Client_Phone, $Client_Email;
    
protected function rules(){
    return  
    [
            'Client_Identity' => 'required|string|max:15',
            'Client_FirstName' => 'required|string|max:100',
            'Client_LastName' => 'required|string|max:100',
            'Client_Email' => 'required|email|max:100|unique:clients,Client_Email,'.  ($this->Client_ID ?? 'null') . ',Client_ID',
            'Client_Address' => 'required|string|max:255',
            'Client_Phone' => 'required|string|max:20'
            
        ];
}
    
    protected $listeners = ['editClientById' => 'loadClientById'];


    public function loadClientById($Client_ID)
    {
        $this->open = true; 
        // Buscar cliente por id
        $client = Client::where('Client_ID', $Client_ID)->firstOrFail();

        // Cargar los datos del cliente en el modal
        $this->Client_ID = $client->Client_ID;
        $this->Client_FirstName = $client->Client_FirstName;
        $this->Client_LastName = $client->Client_LastName;
        $this->Client_Email = $client->Client_Email;
        $this->Client_Address = $client->Client_Address;
        $this->Client_Phone = $client->Client_Phone;
        $this->Client_Identity = $client->Client_Identity;


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
        $this->Client_ID = null;
        $this->Client_FirstName = '';
        $this->Client_LastName = '';
        $this->Client_Email = '';
        $this->Client_Address = '';
        $this->Client_Phone = '';
        $this->Client_Identity = '';
       
    }

    public function update()
    {
  
        $this->validate();
        $client = Client::findOrFail($this->Client_ID);

        // Actualizar los datos del 
        $client->update([
            'Client_Identity' => $this->Client_Identity,
            'Client_FirstName' => $this->Client_FirstName,
            'Client_LastName' => $this->Client_LastName,
            'Client_Address' => $this->Client_Address,
            'Client_Phone' => $this->Client_Phone,
            'Client_Email' => $this->Client_Email
        ]);
       
        $this->resetForm();
        $this->dispatch('clientUpdated');
        $this->dispatch('client-notify', ['message' => 'Cliente actualizado exitosamente.']);
        $this->closeModal();
    }

    public function refreshClients()
{
    $this->resetPage(); 
}
    public function render()
    {
        return view('livewire.clients.client-edit');
    }
}

