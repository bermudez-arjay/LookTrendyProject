<div>
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar Productos</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="text" wire:model.lazy="keyWord"
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-4 pr-10 py-2 sm:text-sm border-gray-300 rounded-md border"
                        placeholder="Nombre....">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Filtrar por fecha</label>
                <input type="date" wire:model.lazy="selectedDate"
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-4 pr-10 py-2 sm:text-sm border-gray-300 rounded-md border">
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Compras (Hoy)</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($incomingToday) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-arrow-down text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 flex items-center">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                    Unidades
                </p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Ventas (Hoy)</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($outgoingToday) }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 flex items-center">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                    Unidades
                </p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Entradas</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($totalIncoming) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-boxes text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 flex items-center">
                    <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                    Unidades
                </p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Salidas</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($totalOutgoing) }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 flex items-center">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                    Unidades
                </p>
            </div>
        </div>
        {{-- <div wire:loading class="fixed inset-0 bg-black bg-opacity-30 z-50">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <div class="animate-spin rounded-full h-24 w-24 border-t-8 border-b-8 border-blue-500 mx-auto"></div>
                <p class="mt-3 text-white font-medium text-center">Cargando...</p>
            </div>
        </div> --}}
    </div>
    <div class="bg-white rounded shadow overflow-hidden mb-6">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">Inventario de Productos</h2>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02] cursor-pointer"
            wire:click="openLowStockModal">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Bajo Stock</p>
                    <p class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-yellow-600' : 'text-gray-600' }}">
                        {{ $lowStockCount }}
                    </p>
                </div>
                <div class="{{ $lowStockCount > 0 ? 'bg-yellow-100' : 'bg-gray-100' }} p-3 rounded-full">
                    <i
                        class="fas fa-exclamation-triangle {{ $lowStockCount > 0 ? 'text-yellow-600' : 'text-gray-600' }} text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 flex items-center">
                    <span
                        class="inline-block w-2 h-2 rounded-full {{ $lowStockCount > 0 ? 'bg-yellow-500' : 'bg-gray-500' }} mr-2"></span>
                    Productos
                </p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock
                            Actual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock
                            Mínimo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($inventoryItems as $item)
                        <tr class="{{ $item->Current_Stock <= $item->Minimum_Stock ? 'bg-yellow-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $item->product->Product_Name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $item->product->Code ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $item->Current_Stock <= $item->Minimum_Stock ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $item->Current_Stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->Minimum_Stock }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($item->Current_Stock <= $item->Minimum_Stock)
                                    <span class="text-yellow-600 font-medium">Bajo Stock</span>
                                @else
                                    <span class="text-green-600 font-medium">Disponible</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $inventoryItems->links() }}
        </div>
    </div>
    @if($showLowStockModal)
        <div class="fixed inset-0 overflow-y-auto z-50">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75" wire:click="closeModal"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Productos con Bajo Stock ({{ $lowStockCount }})
                        </h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Producto</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stock Actual</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stock Mínimo</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Diferencia</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($lowStockProducts as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-medium text-gray-900">
                                                    {{ $product->product->Product_Name ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $product->product->Code ?? '' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-red-600 font-medium">
                                                {{ $product->Current_Stock }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $product->Minimum_Stock }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ $product->Current_Stock - $product->Minimum_Stock }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            wire:click="closeModal">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>