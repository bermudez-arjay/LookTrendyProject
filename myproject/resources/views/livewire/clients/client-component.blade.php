<div>
    <div>
        <div class="mt-[-35px]">
            <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Gestión de Clientes</h1>
            
            <div class="flex items-center text-sm text-gray-500 space-x-2 mb-2">
                <i class="fas fa-home text-gray-400"></i>
                <span>Dashboard</span>
                <span class="text-gray-400">/</span>
                <span class="font-semibold text-indigo-600">Gestión de Clientes</span>
            </div>
            
        </div>
        <div class="overflow-x-auto bg-gray-50 rounded-2xl shadow-inner border border-gray-300 p-8 my-10 mx-auto w-full max-w-[90rem]">   
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Lista de Clientes</h2>
                    </div>
                    <button wire:click="$dispatch('openCreateClientModal')"  data-bs-toggle="modal" data-bs-target="#DataModal"class="bg-indigo-600 text-white px-6 py-3 rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                        Nuevo Cliente
                    </button>
                </div>

                <livewire:clients.client-create />
                <livewire:clients.client-delete/>
                <livewire:clients.client-edit />
              
                <p class="text-sm text-gray-500 mb-6 mt-[-3px]">En la siguiente tabla puede observar la lista de clientes</p>
                <div class="flex space-x-2 items-center">
                    <input 
                        id="searchName"
                        type="text" 
                        placeholder="Buscar Cliente..." 
                        class="input-class border border-gray-300 rounded-lg px-4 py-2 w-full max-w-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        wire:model.live='keyWord'
                    />
                    <button 
                        wire:click="clearFilter"
                        onclick="document.getElementById('searchName').value = ''"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600"
                        
                    >
                        Limpiar
                    </button>
                </div>
            </div>
            
          
    
            <table class="min-w-full divide-y divide-gray-200 bg-white shadow-md rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identificación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellidos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($clients as $client)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $client->Client_Identity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $client->Client_FirstName }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $client->Client_LastName }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $client->Client_Address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $client->Client_Phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $client->Client_Email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-4">
                                <button class="text-red-600 hover:text-red-900" wire:click="$dispatch('showDeleteModal', {Client_ID: '{{ $client->Client_ID }}'})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button class="text-blue-600 hover:text-blue-900" 
                                    wire:click="$dispatch('editClientById', { Client_ID: '{{ $client->Client_ID }}' })">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        
            <div class="mt-4">
                {{ $clients->links() }}
            </div>
        </div>    
    </div>
   
</div>

<script>
       window.addEventListener('client-notify', event => {
        Swal.fire({
            icon: 'success',
            title: event.detail.title || '¡Operación exitosa!',
            text: event.detail.message,
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
</script>
