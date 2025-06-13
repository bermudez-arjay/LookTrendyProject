<div class="p-6 space-y-6 bg-gray-50 min-h-screen">
    <!-- Encabezado y controles -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Reporte de Ventas</h2>
            <p class="text-sm text-gray-500">Filtra y descarga las ventas registradas</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <!-- Botón para mostrar/ocultar filtros -->
            <button wire:click="toggleFilters" 
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                {{ $showFilters ? 'Ocultar Filtros' : 'Mostrar Filtros' }}
            </button>
            
            <!-- Botón de descarga PDF -->
            <button wire:click="exportPDF" 
                    class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                PDF
            </button>
        </div>
    </div>

    <!-- Filtros -->
    @if($showFilters)
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
            <!-- Selector de rango de fechas -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rango de fechas</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <input type="date" wire:model.live="start_date" 
                               class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <span class="self-center text-gray-400 hidden sm:block">—</span>
                    <div class="relative flex-1">
                        <input type="date" wire:model.live="end_date" 
                               class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                <select wire:model.live="client_id" 
                        class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los clientes</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->Client_ID }}">{{ $cliente->Client_FirstName }} {{ $cliente->Client_LastName }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rango de montos</label>
                <div class="flex gap-2">
                    <input type="number" wire:model.live="min_amount" 
                           placeholder="Mínimo" 
                           class="flex-1 pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <input type="number" wire:model.live="max_amount" 
                           placeholder="Máximo" 
                           class="flex-1 pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" wire:model.live="keyWord" 
                       placeholder="ID de venta, nombre de cliente..." 
                       class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>
    @endif

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Ventas hoy -->
        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ventas Hoy</p>
                    <p class="text-lg font-semibold">C$ {{ number_format($totalSalesToday, 2) }}</p>
                </div>
            </div>
        </div>
        
        <!-- Productos vendidos hoy -->
        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Productos Hoy</p>
                    <p class="text-lg font-semibold">{{ $totalQuantityToday }}</p>
                </div>
            </div>
        </div>
        
        <!-- Ventas período -->
     <!-- Ventas período -->
<div class="bg-white p-4 rounded-lg shadow border border-gray-200">
    <div class="flex items-center">
        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Ventas Período</p>
            <p class="text-lg font-semibold">C$ {{ number_format($totalSalesPeriod, 2) }}</p>
        </div>
    </div>
</div>

<!-- Productos período -->
<div class="bg-white p-4 rounded-lg shadow border border-gray-200">
    <div class="flex items-center">
        <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Productos Período</p>
            <p class="text-lg font-semibold">{{ $totalQuantityPeriod }}</p>
        </div>
    </div>
</div>
    </div>

    <!-- Resultados -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <!-- Resumen -->
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200 flex flex-wrap justify-between items-center gap-3">
            <p class="text-sm text-gray-600">
                Mostrando <span class="font-medium">{{ $ventas->count() }}</span> ventas
            </p>
            <p class="text-sm text-gray-600">
                Total período: <span class="font-medium text-blue-600">C$ {{ number_format($ventas->sum('Total_Amount'), 2) }}</span>
            </p>
        </div>
        
        <!-- Tabla de ventas -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"># Venta</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Productos</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">IVA-Aplicado</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total sin IVA</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total + IVA</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($ventas as $venta)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                {{ str_pad($venta->Sale_ID, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ $venta->Sale_Date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ $venta->client ? $venta->client->Client_FirstName . ' ' . $venta->client->Client_LastName : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ $venta->saleDetails->sum('Quantity') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                C$ {{ number_format($venta->Sale_VAT, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                C$ {{ number_format($venta->Total_Amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                C$ {{ number_format($venta->Total_Amount + $venta->Sale_VAT, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                No se encontraron ventas con los filtros aplicados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<!-- After your table closing tag -->
<div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
    {{ $ventas->links() }}
</div>
</div>
@push('scripts')
<script>
    Livewire.on('showSaleDetails', (saleId) => {
        // Implementar modal o página para mostrar detalles de venta
        alert('Mostrar detalles de venta ID: ' + saleId);
    });
</script>
@endpush