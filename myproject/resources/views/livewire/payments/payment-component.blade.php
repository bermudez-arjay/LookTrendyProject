<div class="container mx-auto px-4 py-4">
    <div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Gestión de Abonos</h1>
                <p class="text-gray-600">Registro y seguimiento de pagos a créditos</p>
            </div>



            <button wire:click="create"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-plus mr-2"></i> Nuevo Abono
            </button>
        </div>
    </div>
    @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <p>{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif


<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02]">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Abonos</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">
                    C${{ number_format($totalPaymentAmount, 2) }}
                </p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-wallet text-blue-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-500 flex items-center">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                Histórico de todos los pagos registrados
            </p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02]">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Abonos Este Mes</p>
                <p class="text-2xl font-bold text-gray-800">C${{ number_format($paymentMonth, 0)}}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-500 flex items-center">
                <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                {{ now()->translatedFormat('F Y') }}
            </p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-transform hover:scale-[1.02]">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Último Abono</p>
                <p class="text-2xl font-bold text-gray-800">
                    @if($lastPayment)
                        C${{ number_format($lastPayment->Payment_Amount, 2) }}
                    @else
                        $0.00
                    @endif
                </p>
                @if($lastPayment)
                    <p class="text-sm text-gray-500 mt-1">
                        Crédito #{{ $lastPayment->Credit_ID }}
                    </p>
                @endif
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-history text-purple-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-500 flex items-center">
                <span class="inline-block w-2 h-2 rounded-full bg-purple-500 mr-2"></span>
                {{ $lastPaymentHumanDate }}
            </p>
        </div>
    </div>
</div>


<!-- Tabla de abonos -->
<div class="bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Crédito
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cliente
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Monto
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <a href="{{ route('credits.show', $payment->Credit_ID) }}"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    Crédito #{{ $payment->Credit_ID }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ optional($payment->credit->client)->Client_FirstName }}
                                        {{ optional($payment->credit->client)->Client_LastName }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ optional($payment->credit->client)->Client_Phone ?? '' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $payment->Payment_Date->format('d/m/Y') }}
                                <div class="text-xs text-gray-500">
                                    {{ $payment->Payment_Date->diffForHumans() }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                C${{ number_format($payment->Payment_Amount, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            
                                <button wire:click="generatePdf({{ $payment->Payment_ID }})" title="Descargar PDF"
                                    class="text-blue-500 hover:text-blue-700 p-2 rounded-full hover:bg-blue-100">
                                    <i class="fas fa-file-pdf text-xl"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center justify-center py-8 text-gray-500">
                                <i class="fas fa-money-bill-wave text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg">No se encontraron abonos registrados</p>
                                <p class="text-sm mt-2">Comience registrando un nuevo abono</p>
                                <button wire:click="create"
                                    class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                                    <i class="fas fa-plus mr-2"></i> Nuevo Abono
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($payments->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $payments->links() }}
        </div>
    @endif
</div>

@if ($isOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                wire:click="closeModal"></div>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-money-bill-wave text-indigo-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $paymentId ?  : 'Registrar Nuevo Abono' }}
                            </h3>
                            <div class="mt-2">
                                <!-- Estado del crédito -->
                                @if($credit_id && $selectedCreditInfo)
                                    <div class="mb-4 p-3 rounded-lg {{ $statusClass }}">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <i class="fas fa-info-circle mr-2"></i>
                                                <strong>Estado del crédito:</strong>
                                                {{ $statusMessage }}
                                            </div>
                                            <div>
                                                <strong>Saldo actual:</strong>
                                                C${{ number_format($selectedCreditInfo->remaining_balance, 2) }}
                                            </div>
                                        </div>

                                        @if($this->creditStatus == 'Vencido')
                                            <div class="mt-2 text-sm">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Este crédito ha vencido. Se aplicará mora del 2% si selecciona la opción.
                                            </div>
                                        @endif

                                        @if($this->creditStatus == 'Pendiente' && $is_full_payment)
                                            <div class="mt-2 text-sm">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Puede aplicar un descuento del 5% por pago anticipado completo.
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Campos de mora y descuento -->
                                @if($credit_id && $selectedCreditInfo)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <!-- Campo de mora (si está vencido) -->
                                        @if($this->creditStatus == 'Vencido')
                                            <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                                <label class="flex items-center">
                                                    <input type="checkbox" wire:model="apply_late_fee"
                                                        class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm font-medium text-yellow-800">
                                                        Aplicar mora del 2%
                                                        (C${{ number_format($selectedCreditInfo->Total_Amount * 0.02, 2) }})
                                                    </span>
                                                </label>
                                                @if($apply_late_fee)
                                                    <p class="mt-1 text-xs text-yellow-600">
                                                        El monto total a pagar incluirá la mora.
                                                    </p>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Campo de descuento (si se cancela antes de vencimiento) -->
                                        @if($this->creditStatus == 'Pendiente' && $is_full_payment)
                                            <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                                                <label class="flex items-center">
                                                    <input type="checkbox" wire:model="apply_early_discount"
                                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm font-medium text-blue-800">
                                                        Aplicar descuento del 5% por pago anticipado
                                                        (C${{ number_format($selectedCreditInfo->remaining_balance * 0.05, 2) }})
                                                    </span>
                                                </label>
                                                @if($apply_early_discount)
                                                    <p class="mt-1 text-xs text-blue-600">
                                                        El monto total a pagar se reducirá con el descuento.
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <!-- Búsqueda de créditos -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Crédito</label>
                                    <div class="relative">
                                        <input wire:model.live="searchCredit" type="text"
                                            placeholder="Buscar créditos por ID o nombre de cliente..."
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <div class="absolute left-3 top-2.5 text-gray-400">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabla de créditos -->
                                <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="max-h-64 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50 sticky top-0">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Seleccionar
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Crédito
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Cliente
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Saldo
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse ($credits as $credit)
                                                    <tr
                                                        class="hover:bg-gray-50 @if($credit_id == $credit->Credit_ID) bg-blue-50 @endif">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="radio" wire:model.live="credit_id"
                                                                value="{{ $credit->Credit_ID }}"
                                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                                name="credit_selection">
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                #{{ $credit->Credit_ID }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">
                                                                ${{ number_format($credit->Total_Amount, 2) }} (Total)
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div
                                                                    class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                                    <i class="fas fa-user text-indigo-600"></i>
                                                                </div>
                                                                <div class="ml-4">
                                                                    <div class="text-sm font-medium text-gray-900">
                                                                        {{ optional($credit->client)->Client_FirstName }}
                                                                        {{ optional($credit->client)->Client_LastName }}
                                                                    </div>
                                                                    <div class="text-sm text-gray-500">
                                                                        {{ optional($credit->client)->Client_Phone ?? '' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                                                {{ $credit->remaining_balance > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                                ${{ number_format($credit->remaining_balance, 2) }}
                                                            </span>
                                                            @if($credit_id == $credit->Credit_ID && ($dollar_amount !== null || $cordoba_amount !== null))
                                                                @php
                                                                    $amount = 0;
                                                                    if ($payment_type_id == 2 && is_numeric($dollar_amount)) {
                                                                        $amount = floatval($dollar_amount) * floatval($exchangeRate);
                                                                    } elseif (is_numeric($cordoba_amount)) {
                                                                        $amount = floatval($cordoba_amount);
                                                                    }
                                                                    $newBalance = max(0, $credit->remaining_balance - $amount);
                                                                @endphp
                                                                <div class="text-xs text-gray-500 mt-1">
                                                                    Nuevo saldo: C${{ number_format($newBalance, 2) }}
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                                            No se encontraron créditos disponibles
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Campos de fecha y tipo de pago -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <!-- Fecha de Abono -->
                                    <div>
                                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha
                                            de Abono *</label>
                                        <div class="relative">
                                            <input wire:model="payment_date" type="date" id="payment_date"
                                                class="block w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <div class="absolute right-3 top-2.5 text-gray-400">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        @error('payment_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Tipo de Pago -->
                                    <div>
                                        <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-1">
                                            Tipo de Pago *
                                        </label>
                                        <select wire:model.live="payment_type_id" id="payment_type"
                                            wire:change="updatePaymentFields"
                                            class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            @if(!$credit_id) disabled @endif>
                                            <option value="">Seleccione un tipo</option>
                                            @foreach($paymentTypes as $type)
                                                <option value="{{ $type->Payment_Type_ID }}">
                                                    {{ $type->Payment_Type_Name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('payment_type_id')
                                            <p class="mt-1 text-sm text-red-600">{{$message }}</p>
                                        @enderror
                                        @if(!$credit_id)
                                            <p class="mt-1 text-xs text-gray-500">Seleccione un crédito primero</p>
                                        @endif
                                    </div>
                                </div>

                      @if($show_amount_fields)
    <div class="space-y-4 mb-4">
        <!-- Sección de Saldo y Monto a Pagar -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Saldo Pendiente -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Saldo Pendiente</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500">C$</span>
                    </div>
                    <input type="text"
                           value="{{ number_format($this->baseBalance, 2) }}"
                           readonly
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>
            </div>

            <!-- Monto Total a Pagar -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Monto a Pagar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500">C$</span>
                    </div>
                    <input type="text"
                           value="{{ number_format($this->totalPaymentAmount, 2) }}"
                           readonly
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-blue-50 font-medium">
                </div>
                @if($apply_late_fee)
                    <p class="text-xs text-red-500 mt-1">Incluye mora del 2%: C${{ number_format($this->baseBalance * 0.02, 2) }}</p>
                @elseif($apply_early_discount)
                    <p class="text-xs text-green-500 mt-1">Incluye descuento del 5%: C${{ number_format($this->baseBalance * 0.05, 2) }}</p>
                @endif
            </div>
        </div>

        <!-- Monto Recibido -->
       <div>
    <label class="block text-sm font-medium text-gray-700">
        Monto Recibido *
        @if($payment_type_id == 2)
            <span class="text-xs text-gray-500">(en dólares)</span>
        @else
            <span class="text-xs text-gray-500">(en córdobas)</span>
        @endif
    </label>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500">
                @if($payment_type_id == 2) $ @else C$ @endif
            </span>
        </div>
        <input wire:model.live="{{ $payment_type_id == 2 ? 'dollar_amount' : 'cordoba_amount' }}" 
               type="number" 
               step="0.01" 
               min="0.01"
               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
               placeholder="0.00"
               required>
    </div>
    @error($payment_type_id == 2 ? 'dollar_amount' : 'cordoba_amount')
        <span class="text-red-500 text-xs">{{$message }}</span>
    @enderror
</div>

        <!-- Conversión de moneda si es pago en dólares -->
        @if($payment_type_id == 2)
            <div>
                <label class="block text-sm font-medium text-gray-700">Equivalente en Córdobas</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500">C$</span>
                    </div>
                    <input type="text" 
                           value="{{ number_format($this->cordoba_amount, 2) }}" 
                           readonly
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>
            </div>
        @endif

        <!-- Mostrar cambio si hay -->
        @if($show_change_field)
            <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                <label class="block text-sm font-medium text-green-700">Cambio a Entregar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500">C$</span>
                    </div>
                    <input type="text"
                           value="C${{ number_format($change_amount, 2) }}"
                           readonly
                           class="block w-full pl-10 pr-3 py-2 border border-green-300 rounded-lg bg-green-50 font-bold">
                </div>
            </div>
        @endif

        <!-- Nuevo saldo -->
        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg text-sm">
            <i class="fas fa-info-circle mr-2"></i>
            Nuevo saldo después del pago: C${{ 
                number_format(max(0, $this->baseBalance - 
                ($payment_type_id == 2 ? floatval($dollar_amount) * $exchangeRate : floatval($cordoba_amount))), 2) 
            }}
        </div>
    </div>
@endif

<!-- Botones de acción -->
<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
    <button type="button" wire:click="store"
        class="inline-flex justify-center w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Guardar
    </button>
    <button type="button" wire:click="closeModal"
        class="mt-3 inline-flex justify-center w-full sm:w-auto px-4 py-2 bg-white text-gray-700 text-sm font-medium border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3">
        Cancelar
    </button>
</div>
@endif

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('amounts-reset', () => {
            const cordobaInput = document.getElementById('cordoba_amount');
            if (cordobaInput) {
                cordobaInput.value = 36.50;
                cordobaInput.dispatchEvent(new Event('input'));
            }
        });
    });
</script>