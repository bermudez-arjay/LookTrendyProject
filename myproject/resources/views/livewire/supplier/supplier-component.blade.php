<div>
    <div>
        <div class="mt-[-35px]">
            <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Gestión de Proveedores</h1>

            <div class="flex items-center text-sm text-gray-500 mb-[-25px]">
                <span class="text-gray-500">dashboard</span>
                <span class="mx-2">→</span>
                <span class="text-gray-500 font-medium">Gestión de proveedores</span>
            </div>
        </div>
        <div
            class="overflow-x-auto bg-gray-50 rounded-2xl shadow-inner border border-gray-300 p-8 my-10 mx-auto w-full max-w-[90rem]">
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Lista de Proveedores</h2>
                    </div>
                    <button wire:click="$dispatch('openCreateSupplierModal')"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Nuevo Proveedor
                    </button>
                </div>

                {{-- Llamadas a los subComponentes --}}
                <livewire:supplier.supplier-create />
                <livewire:supplier.supplier-edit />
                <livewire:supplier.supplier-delete />

                <p class="text-sm text-gray-500 mb-6 mt-[-3px]">En la siguiente tabla puede observar la lista de
                    proveedores</p>
                <div class="flex space-x-2 items-center">
                    <input id="searchEmail" type="text" placeholder="Buscar por Email..."
                        class="border border-gray-300 rounded-lg px-4 py-2 w-full max-w-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        wire:model="searchEmail">

                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                        wire:click="filterByEmail">
                        Filtrar
                    </button>
                    <button class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600"
                        wire:click="clearFilter">
                        Limpiar
                    </button>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="text-green-600 mb-4">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="text-red-600 mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <table class="min-w-full divide-y divide-gray-200 bg-white shadow-md rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Identificación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RUC
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Teléfono</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dirección</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $supplier->Supplier_Identity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $supplier->Supplier_Name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $supplier->Supplier_Email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $supplier->Supplier_RUC }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $supplier->Supplier_Phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $supplier->Supplier_Address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-4">
                                <button class="text-red-600 hover:text-red-900"
                                    wire:click="$dispatch('showDeleteModal', {Supplier_ID: '{{ $supplier->Supplier_ID }}'})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button class="text-blue-600 hover:text-blue-900"
                                    wire:click="$dispatch('editSupplierByEmail', { email: '{{ $supplier->Supplier_Email }}' })">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>