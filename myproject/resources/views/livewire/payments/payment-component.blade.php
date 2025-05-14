<div class="container mx-auto px-4 py-4">
<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Gestión de Abonos</h1>
            <p class="text-gray-600">Registro y seguimiento de pagos a créditos</p>
        </div>
        
       
            
            <button 
                wire:click="create"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors"
            >
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
                ${{ number_format($totalPaymentAmount, 2) }}
            </p>                </div>
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
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($paymentMonth,0)}}</p>
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
                            ${{ number_format($lastPayment->Payment_Amount, 2) }}
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Crédito
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Monto
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('credits.show', $payment->Credit_ID) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Crédito #{{ $payment->Credit_ID }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
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
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    ${{ number_format($payment->Payment_Amount, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <button 
                                        wire:click="edit({{ $payment->Payment_ID }})" 
                                        class="text-indigo-600 hover:text-indigo-900"
                                        title="Editar"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        wire:click="confirmDelete({{ $payment->Payment_ID }})" 
                                        class="text-red-600 hover:text-red-900"
                                        title="Eliminar"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button wire:click="generatePdf({{ $payment->Payment_ID }})" 
                                        title="Descargar PDF"
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
                                    <button 
                                        wire:click="create"
                                        class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors"
                                    >
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
          
            <div 
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                aria-hidden="true"
                wire:click="closeModal"
            ></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-money-bill-wave text-indigo-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $paymentId ? 'Editar Abono' : 'Registrar Nuevo Abono' }}
                            </h3>
                            <div class="mt-2">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Crédito</label>
                                    <div class="relative">
                                        <input 
                                            wire:model.live="searchCredit"
                                            type="text" 
                                            placeholder="Buscar créditos por ID o nombre de cliente..." 
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        >
                                        <div class="absolute left-3 top-2.5 text-gray-400">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                                
 
                            <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                                <div class="max-h-64 overflow-y-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 sticky top-0">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Seleccionar
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Crédito
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Cliente
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Saldo
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse ($credits as $credit)
                                            <tr class="hover:bg-gray-50 @if($credit_id == $credit->Credit_ID) bg-blue-50 @endif">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input 
                                                    type="radio" 
                                                    wire:model.live="credit_id"
                                                    value="{{ $credit->Credit_ID }}"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                    name="credit_selection"
                                                />
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">#{{ $credit->Credit_ID }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        ${{ number_format($credit->Total_Amount, 2) }} (Total)
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
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
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $credit->remaining_balance > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                        ${{ number_format($credit->remaining_balance, 2) }}
                                                    </span>
                                                    @if($credit_id == $credit->Credit_ID && $payment_amount)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Nuevo saldo: ${{ number_format(max(0, $credit->remaining_balance - $payment_amount), 2) }}
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
                                
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                      
                                        <div>
                                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Abono *</label>
                                            <div class="relative">
                                                <input 
                                                    wire:model.live="payment_date"
                                                    type="date" 
                                                    id="payment_date"
                                                    class="block w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                >
                                                <div class="absolute right-3 top-2.5 text-gray-400">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                            </div>
                                            @error('payment_date') 
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                    
                                        <div>
                                            <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-1">Monto del Abono *</label>
                                            <div class="relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500">$</span>
                                                </div>
                                                <input 
                                                    wire:model.live="payment_amount"
                                                    type="number" 
                                                    step="0.01"
                                                    min="0.01"
                                                    id="payment_amount"
                                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-lg"
                                                    placeholder="0.00"
                                                >
                                            </div>
                                         
                                            
                                            @if($credit_id)
                                                @php
                                                    $selectedCredit = $credits->firstWhere('Credit_ID', $credit_id);
                                                    $maxAmount = $selectedCredit ? $selectedCredit->remaining_balance : 0;
                                                @endphp
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Saldo pendiente: ${{ number_format($maxAmount, 2) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            wire:click="store"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                            @if(!$credit_id || !$payment_amount) disabled @endif
                        >
                            <i class="fas fa-save mr-2"></i> 
                            {{ $paymentId ? 'Actualizar Abono' : 'Registrar Abono' }}
                        </button>
                        <button 
                            wire:click="closeModal"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('confirmDelete', paymentId => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('delete', paymentId);
                        Swal.fire(
                            '¡Eliminado!',
                            'El abono ha sido eliminado.',
                            'success'
                        );
                    }
                });
            });
        });
    </script>
</div>