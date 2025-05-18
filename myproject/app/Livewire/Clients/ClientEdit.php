<?php
namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;

class ClientEdit extends Component
{
    public $open = false;

  public $Client_ID;
    public  $Client_Identity, $Client_FirstName, $Client_LastName, $Client_Address, $Client_Phone, $Client_Email;
    
protected $rules = [
        'Client_Identity' => [
            'required',
            'string',
            'max:16',
            'regex:/^\d{3}-\d{6}-\d{4}[A-Za-z]$/',
        ],
        'Client_FirstName' => 'required|string|max:100|min:6|regex:/^[\pL\s\-]+$/u',
        'Client_LastName' => 'required|string|max:100|regex:/^[\pL\s\-]+$/u',
        'Client_Email' => 'required|email|max:100|unique:clients,Client_Email',
        'Client_Address' => 'required|string|max:255',
        'Client_Phone' => 'required|string|min:8|max:8|regex:/^[2|5|7|8]\d{7}$/',
    ];

    public function messages()
    {
        return [
            'Client_Identity.required' => 'La cédula es obligatoria.',
            'Client_Identity.regex' => 'El formato de la cédula no es válido.',
            'Client_FirstName.required' => 'El nombre es obligatorio.',
            'Client_FirstName.regex' => 'El nombre solo puede contener letras y espacios.',
            'Client_LastName.required' => 'El apellido es obligatorio.',
            'Client_LastName.regex' => 'El apellido solo puede contener letras y espacios.',
            'Client_Email.required' => 'El correo electrónico es obligatorio.',
            'Client_Email.email' => 'Ingrese un correo electrónico válido.',
            'Client_Email.max' => 'El correo no debe exceder 100 caracteres.',
            'Client_Email.unique' => 'Este correo electrónico ya está registrado.',
            'Client_Address.required' => 'La dirección es obligatoria.',
            'Client_Address.max' => 'La dirección no debe exceder 255 caracteres.',
            'Client_Phone.required' => 'El teléfono es obligatorio.',
            'Client_Phone.max' => 'El teléfono debe tener exactamente 8 dígitos.',
            'Client_Phone.min' => 'El teléfono debe tener exactamente 8 dígitos.',
            'Client_Phone.regex' => 'Ingrese un número de teléfono nicaragüense válido (debe comenzar con 2, 5, 7 u 8).',
        ];
    }

    private function validarCedulaNicaraguense($cedula)
    {
        if (!preg_match('/^\d{3}-\d{6}-\d{4}[A-Za-z]$/', $cedula)) {
            return false;
        }

        $partes = explode('-', $cedula);
        $letra = strtoupper(substr($cedula, -1));
        if (!ctype_digit($partes[0]) || !ctype_digit($partes[1]) || !ctype_digit(substr($partes[2], 0, 4))) {
            return false;
        }

        if (!ctype_alpha($letra)) {
            return false;
        }
        $numeros = str_replace('-', '', substr($cedula, 0, -1));
        $suma = array_sum(str_split($numeros));
        $letraCalculada = chr(($suma % 26) + 65); 

        return ($letra === $letraCalculada);
    }

    protected $listeners = ['editClientById' => 'loadClientById'];


    public function loadClientById($Client_ID)
    {
        $this->open = true; 
      
        $client = Client::where('Client_ID', $Client_ID)->firstOrFail();
        $this->Client_ID = $client->Client_ID;
        $this->Client_FirstName = $client->Client_FirstName;
        $this->Client_LastName = $client->Client_LastName;
        $this->Client_Email = $client->Client_Email;
        $this->Client_Address = $client->Client_Address;
        $this->Client_Phone = $client->Client_Phone;
        $this->Client_Identity = $client->Client_Identity;


    }

    public function closeModal()
    {
        $this->resetForm();
        $this->open = false;
    }

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

