<div class="p-6 space-y-6 bg-gray-50 min-h-screen">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Reporte de Abonos</h2>
            <p class="text-sm text-gray-500">Filtra los abonos por fecha, cliente y tipo de pago</p>
        </div>

        <!-- Botón PDF -->
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
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
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
            <!-- Fechas -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rango de fechas</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="date" wire:model.live="start_date" class="flex-1 pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <span class="self-center text-gray-400 hidden sm:block">—</span>
                    <input type="date" wire:model.live="end_date" class="flex-1 pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Crédito -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Crédito</label>
                <select wire:model.live="credit_id" class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los créditos</option>
                    @foreach($credits as $credit)
                        <option value="{{ $credit->Credit_ID }}">#{{ $credit->Credit_ID }} - {{ $credit->client->Client_FirstName }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <!-- Tipo de pago -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de pago</label>
                <select wire:model.live="payment_type_id" class="w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los tipos</option>
                    @foreach($paymentTypes as $type)
                        <option value="{{ $type->Payment_Type_ID }}">{{ $type->Payment_Type_Name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Rango de montos -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rango de montos</label>
                <div class="flex gap-2">
                    <input type="number" wire:model.live="min_amount" placeholder="Mínimo" class="flex-1 pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <input type="number" wire:model.live="max_amount" placeholder="Máximo" class="flex-1 pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <!-- Resumen -->
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200 flex flex-wrap justify-between items-center gap-3">
            <p class="text-sm text-gray-600">
                Mostrando <span class="font-medium">{{ count($payments) }}</span> abonos
            </p>
            <p class="text-sm text-gray-600">
                Total abonado: <span class="font-medium text-green-600">C$ {{ number_format($payments->sum('Payment_Amount'), 2) }}</span>
            </p>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"># Abono</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"># Crédito</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $payment->Payment_ID }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->Credit_ID }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->credit->client->Client_FirstName ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->Payment_Date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">C$ {{ number_format($payment->Payment_Amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->paymentType->Payment_Type_Name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No se encontraron abonos con los filtros aplicados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
