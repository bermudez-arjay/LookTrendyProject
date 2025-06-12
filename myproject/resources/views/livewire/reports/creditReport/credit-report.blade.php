<div class="p-6 space-y-6 bg-gray-50 min-h-screen">
    <!-- Encabezado y controles -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Reporte de Créditos</h2>
            <p class="text-sm text-gray-500">Filtra y descarga los créditos registrados</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
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
                        <input type="date" wire:model.live="due_date" 
                               class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
           >
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                <select wire:model.live="client_id" 
                        class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los clientes</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->Client_ID }}">{{ $cliente->Client_FirstName }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
          
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select wire:model.live="credit_status" 
                        class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="cancelado">Cancelado</option>
                    <option value="vencido">Vencido</option>
                </select>
            </div>
         
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
        </div>
    </div>

    <!-- Resultados -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <!-- Resumen -->
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200 flex flex-wrap justify-between items-center gap-3">
            <p class="text-sm text-gray-600">
                Mostrando <span class="font-medium">{{ $creditos->count() }}</span> créditos
            </p>
            <p class="text-sm text-gray-600">
                Total pendiente: <span class="font-medium text-blue-600">C$ {{ number_format($creditos->sum('remaining_balance'), 2) }}</span>
            </p>
        </div>
        
        <!-- Tabla de créditos -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"># Crédito</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inicio</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vence</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($creditos as $credito)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $credito->Credit_ID }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $credito->client->Client_FirstName ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $credito->Start_Date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="{{ $credito->is_expired ? 'text-red-600' : '' }}">
                                    {{ $credito->formatted_due_date }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                C$ {{ number_format($credito->Total_Amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $credito->computed_status == 'Cancelado' ? 'bg-green-100 text-green-800' : 
                                       ($credito->computed_status == 'Vencido' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $credito->computed_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                                {{ $credito->remaining_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                C$ {{ number_format($credito->remaining_balance, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="showDetails({{ $credito->Credit_ID }})" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                    Detalles
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                No se encontraron créditos con los filtros aplicados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>