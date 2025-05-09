        
 
           <div class="max-w-full px-8 mx-auto p-6 bg-white rounded-xl shadow-lg border border-gray-200" >
            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">Crear Nuevo Crédito</h2>
        
            <form wire:submit.prevent="save" class="space-y-6">
                <!-- Sección Cliente y Plazo -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Cliente -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Cliente <span class="text-red-500">*</span></label>
                        <select wire:model.live="client_id" id="client_id" 
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                            <option value="">Seleccione un cliente</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->Client_ID }}">{{ $client->Client_FirstName }} {{ $client->Client_LastName }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
        
                    <!-- Plazo -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Plazo (Meses) <span class="text-red-500">*</span></label>
                        <select wire:model.live="term" id="term" 
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                            <option value="">Seleccione el plazo</option>
                            <option value="1">1 mes</option>
                            <option value="3">3 meses</option>
                            <option value="6">6 meses</option>
                        </select>
                        @error('term') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
        
                <!-- Sección Fechas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Fecha de Inicio -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Fecha de Inicio <span class="text-red-500">*</span></label>
                        <input type="date" wire:model.live="start_date" 
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                        @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
        
                    <!-- Fecha de Vencimiento -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Fecha de Vencimiento</label>
                        <input type="date" value="{{ $due_date }}" readonly 
                            class="mt-1 block w-full rounded-lg bg-gray-50 border-gray-300 shadow-sm py-2 px-3 border">
                    </div>
                </div>
        
                <!-- Sección Tipo de Pago -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Tipo de Pago <span class="text-red-500">*</span></label>
                    <select wire:model.live="payment_type_id" id="payment_type_id" 
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                        <option value="">Seleccione tipo de pago</option>
                        @foreach($paymentTypes as $type)
                            <option value="{{ $type->Payment_Type_ID }}">{{ $type->Payment_Type_Name }}</option>
                        @endforeach
                    </select>
                    @error('payment_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
        
                <!-- Sección Productos -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-800">Productos</h3>
                        <button type="button" wire:click="$set('showProductModal', true)" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Agregar Producto
                        </button>
                    </div>
        
                    @if(count($creditDetails))
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">  
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IVA</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($creditDetails as $index => $detail)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail['product_name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail['quantity'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($detail['subtotal'], 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($detail['vat'], 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($detail['total_with_vat'], 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button type="button" wire:click="doRemoveDetail({{ $index }})" 
                                                    class="text-red-600 hover:text-red-900">
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6 bg-gray-50 rounded-lg">
                            <p class="text-gray-500">No hay productos agregados</p>
                        </div>
                    @endif
                </div>
        
                <!-- Sección Totales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 p-4 rounded-lg">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Total con Interés</label>
                        <input type="text" value="${{ number_format($totalWithInterest, 2) }}" readonly 
                            class="mt-1 block w-full rounded-lg bg-white border-gray-300 shadow-sm py-2 px-3 border font-medium text-gray-900">
                    </div>
        
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Monto por Cuota</label>
                        <input type="text" value="${{ number_format($quotaAmount, 2) }}" readonly 
                            class="mt-1 block w-full rounded-lg bg-white border-gray-300 shadow-sm py-2 px-3 border font-medium text-gray-900">
                    </div>
        
                    <div class="flex items-end">
                        <button type="submit" 
                            class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar Crédito
                        </button>
                    </div>
                </div>
            </form>
        
            <!-- Modal de Producto -->
            @if($showProductModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Agregar Producto</h3>
                            <button wire:click="$set('showProductModal', false)" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
            
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Producto <span class="text-red-500">*</span></label>
                                <select wire:model.live="product_id" 
                                id="product_id" 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                            <option value="">Seleccione un producto</option>
                            @foreach($products as $product)
                                <option value="{{ $product->Product_ID }}">{{ $product->Product_Name }}</option>
                            @endforeach
                        </select>
                            </div>
            
                            <!-- Campos de Stock y Precio que se actualizarán al seleccionar producto -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Stock Disponible</label>
                                    <input type="text" value= {{$selectedStock}} readonly 
                                        class="mt-1 block w-full rounded-lg bg-gray-50 border-gray-300 shadow-sm py-2 px-3 border">
                                </div>
            
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Precio Unitario</label>
                                    <input type="text" value="C${{ number_format($selectedPrice, 2) }}" readonly 
                                        class="mt-1 block w-full rounded-lg bg-gray-50 border-gray-300 shadow-sm py-2 px-3 border">
                                </div>
                            </div>
            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Cantidad <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="quantity" min="1" :max="$selectedStock"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                            
                            </div>
                        </div>
            
                        <div class="mt-6 flex justify-end space-x-3">
                            <button wire:click="$set('showProductModal', false)" 
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </button>
                            <button wire:click="addDetail" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    
    document.addEventListener('livewire:initialized', () => {
    Livewire.on('swal:success', (event) => {
        Swal.fire({
            icon: 'success',
            title: event.title || 'Éxito',
            text: event.message,
            timer: event.timer || 3000,
            showConfirmButton: !event.timer
        });
    });

    Livewire.on('swal:error', (event) => {
        Swal.fire({
            icon: 'error',
            title: event.title || 'Error',
            text: event.message
        });
    });
});
window.addEventListener('swal:success', event => {
    Swal.fire({
        icon: 'success',
        title: event.detail.title || 'Éxito',
        text: event.detail.message,
        timer: event.detail.timer || 3000,
        showConfirmButton: !event.detail.timer,
        confirmButtonColor: '#3085d6',
    }).then((result) => {
        if (event.detail.callback) {
            eval(event.detail.callback);
        }
    });
});

window.addEventListener('swal:error', event => {
    Swal.fire({
        icon: 'error',
        title: event.detail.title || 'Error',
        text: event.detail.message,
        confirmButtonColor: '#d33',
    });
});

window.addEventListener('swal:confirm', event => {
    Swal.fire({
        icon: 'question',
        title: event.detail.title || 'Confirmación',
        text: event.detail.message,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: event.detail.confirmText || 'Sí, continuar',
        cancelButtonText: event.detail.cancelText || 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch(event.detail.method, event.detail.params || []);
        }
    });
});
</script>
@endpush
</div>