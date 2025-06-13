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
 <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total de créditos -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Créditos</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalCredits }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-file-invoice-dollar text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                    Monto total: C$ {{ number_format($totalAmount, 2) }}
                </p>
            </div>
        </div>

        <!-- Promedio por crédito -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Promedio por crédito</p>
                    <p class="text-2xl font-bold text-purple-600">C$ {{ number_format($averageCredit, 2) }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    <span class="inline-block w-2 h-2 rounded-full bg-purple-500 mr-2"></span>
                    En {{ $daysPeriod }} días
                </p>
            </div>
        </div>

        <!-- Créditos cancelados -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Cancelados</p>
                    <p class="text-2xl font-bold text-green-600">{{ $paidCredits }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                    {{ $totalCredits > 0 ? round(($paidCredits/$totalCredits)*100, 1) : 0 }}% del total
                </p>
            </div>
        </div>

        <!-- Créditos vencidos -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Vencidos</p>
                    <p class="text-2xl font-bold text-red-600">{{ $expiredCredits }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                    {{ $totalCredits > 0 ? round(($expiredCredits/$totalCredits)*100, 1) : 0 }}% del total
                </p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
            <!-- Selector de rango de fechas -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rango de fechas</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Fecha de inicio</label>
                        <input 
                            type="date" 
                            wire:model.live="start_date" 
                            class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           
                        >
                        @error('start_date')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <span class="self-center text-gray-400 hidden sm:block pt-5">—</span>
                    <div class="relative flex-1">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Fecha de fin</label>
                        <input 
                            type="date" 
                            wire:model.live="due_date" 
                            class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           
                        >
                        @error('due_date')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                <select wire:model.live="client_id" 
                        class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los clientes</option>
                    @foreach($clients as $cliente)
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
                Mostrando <span class="font-medium">{{ $credits->count() }}</span> créditos
            </p>
            <p class="text-sm text-gray-600">
                Total pendiente: <span class="font-medium text-blue-600">C$ {{ number_format($credits->sum('remaining_balance'), 2) }}</span>
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
                      
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($credits as $credito)
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