<div class="max-w-full px-8 mx-auto p-6 bg-white rounded-xl shadow-lg border border-gray-200">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">Registrar Venta</h2>

    @if (session()->has('success'))
        <div class="p-3 bg-green-100 text-green-800 rounded-lg mb-4">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="saveSale" class="space-y-6">
        <!-- Sección Cliente y Fecha -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Vendedor</label>
                <input type="text" value="{{ Auth::user()->User_FirstName }}"
                    class="mt-1 block w-full rounded-lg bg-gray-50 border-gray-300 shadow-sm py-2 px-3 border" readonly>
                <input type="hidden" wire:model="userId">
            </div>
            <!-- Cliente -->
            <div class="space-y-2">
    <label class="block text-sm font-medium text-gray-700">Cliente <span class="text-red-500">*</span></label>
    <select wire:model="selectedClientId"
        class="mt-1 block w-full rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border @error('selectedClientId') border-red-500 @enderror">
        <option value="">Seleccionar cliente</option> 
        @foreach ($clients as $client)
            <option value="{{ $client->Client_ID }}">{{ $client->Client_FirstName }} {{ $client->Client_LastName }}</option>
        @endforeach
    </select>
    @error('selectedClientId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>
            <!-- Fecha -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Fecha <span class="text-red-500">*</span></label>
                <input type="date" wire:model="saleDate" readonly
                    class="mt-1 block w-full rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border @error('saleDate') border-red-500 @enderror">
                @error('saleDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <!-- Sección Productos -->
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-800">Productos <span class="text-red-500">*</span></h3>
                <button type="button" wire:click="$set('showProductModal', true)"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Agregar Producto
                </button>
            </div>

            @if(count($productList))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unitario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IVA (15%)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($productList as $index => $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail['product_name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail['quantity'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">C${{ number_format($detail['subtotal'] / $detail['quantity'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">C${{ number_format($detail['subtotal'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">C${{ number_format($detail['vat'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">C${{ number_format($detail['total_amount'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button type="button" wire:click="removeProduct({{ $index }})" class="text-red-600 hover:text-red-900">
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

        <!-- Sección Totales y Método de Pago -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-gray-50 p-4 rounded-lg">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                <input type="text" value="C${{ number_format(collect($productList)->sum('subtotal'), 2) }}" readonly
                    class="mt-1 block w-full rounded-lg bg-white border-gray-300 shadow-sm py-2 px-3 border font-medium text-gray-900">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">IVA (15%)</label>
                <input type="text" value="C${{ number_format(collect($productList)->sum('vat'), 2) }}" readonly
                    class="mt-1 block w-full rounded-lg bg-white border-gray-300 shadow-sm py-2 px-3 border font-medium text-gray-900">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Total</label>
                <input type="text" value="C${{ number_format(collect($productList)->sum('total_amount'), 2) }}" readonly
                    class="mt-1 block w-full rounded-lg bg-white border-gray-300 shadow-sm py-2 px-3 border font-bold text-gray-900">
            </div>

           <div class="space-y-2">
    <label class="block text-sm font-medium text-gray-700">Método de Pago <span class="text-red-500">*</span></label>
    <select wire:model="payment_type_id" wire:change="updatePaymentFields"
        class="mt-1 block w-full rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border @error('payment_type_id') border-red-500 @enderror">
        <option value="">Seleccionar método</option>
        @foreach($paymentTypes as $type)
            <option value="{{ $type->Payment_Type_ID }}">{{ $type->Payment_Type_Name }}</option>
        @endforeach
    </select>
    @error('payment_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>

   @if($show_amount_fields)
    @if($payment_type_id == 2)
     
        <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700">
                Monto en Dólares <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500">$</span>
                </div>
                <input wire:model.live="dollar_amount" 
                       type="number" step="0.01" min="0.01"
                       class="block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('dollar_amount') border-red-500 @enderror"
                       placeholder="0.00">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <span class="text-gray-500">USD</span>
                </div>
            </div>
            @error('dollar_amount') <span class="text-red-500 text-xs">{{$message }}</span> @enderror

            <label class="block text-sm font-medium text-gray-700">Equivalente en Córdobas</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500">C$</span>
                </div>
                <input type="text" 
                       value="C${{ number_format($this->convertedAmount, 2) }}" 
                       readonly
                       class="block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-lg bg-gray-50">
            </div>
        </div>
    @else
    
        <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700">
                Monto Recibido (Córdobas) <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500">C$</span>
                </div>
                <input wire:model.live="cordoba_amount" 
                       type="number" step="0.01" min="0.01"
                       class="block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('cordoba_amount') border-red-500 @enderror"
                       placeholder="0.00">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <span class="text-gray-500">NIO</span>
                </div>
            </div>
            @error('cordoba_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    @endif
    @if($change_amount > 0)
        <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700">
                Vuelto (Córdobas)
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500">C$</span>
                </div>
                <input type="text" 
                       readonly 
                       value="C${{ number_format($change_amount, 2) }}"
                       class="block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-lg bg-gray-50">
            </div>
        </div>
    @endif
@endif
    <div class="md:col-span-4 flex justify-end space-x-3">
        <button type="button" wire:click="cancelSale"
            class="w-32 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            Cancelar
        </button>
        <button type="submit"
            class="w-32 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Guardar Venta
        </button>
    </div>
</div>
    </form>

    @if($showProductModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" wire:click="$set('showProductModal', false)"></div>
                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-4xl overflow-hidden">
                  
                    <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Seleccionar Producto</h3>
                                <p class="mt-1 text-sm text-gray-500">Busque y seleccione productos para agregar</p>
                            </div>
                            <button wire:click="$set('showProductModal', false)" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-4 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="productSearch"
                                placeholder="Buscar productos..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    @error('modal_error')
                        <div class="bg-red-50 text-red-600 p-3 mx-6 rounded-lg">
                            {{$message }}
                        </div>
                    @enderror
                    <div class="px-6 pb-4">
                        <div class="overflow-y-auto max-h-96">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                                    </tr>
                                </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($products as $product)
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="font-medium text-gray-900">{{ $product['Product_Name'] }}</div>
                <div class="text-sm text-gray-500">{{ $product['Category'] }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                    {{ ($product['inventories']['Current_Stock'] ?? 0) > 5 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $product['inventories']['Current_Stock'] ?? 0 }} unidades
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                C${{ number_format($product['Unit_Price'], 2) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
            <input 
                type="number" 
                wire:model="quantities.{{ $product['Product_ID'] }}"
                min="1" 
                max="{{ $product['inventories']['Current_Stock'] ?? 0 }}"
                class="w-20 px-2 py-1 border rounded-md sm:text-sm @error('quantity_'.$product['Product_ID']) border-red-500 @enderror"
                {{ ($product['inventories']['Current_Stock'] ?? 0) <= 0 ? 'disabled' : '' }}
            >
            @error('quantity_'.$product['Product_ID'])
                <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
            @enderror
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <button 
                wire:click="addProduct({{ $product['Product_ID'] }})"
                class="text-indigo-600 hover:text-indigo-900 px-3 py-1 rounded @if(($product['inventories']['Current_Stock'] ?? 0) <= 0) opacity-50 cursor-not-allowed @endif"
                @if(($product['inventories']['Current_Stock'] ?? 0) <= 0) disabled @endif
            >
                Agregar
            </button>
        </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                No se encontraron productos
            </td>
        </tr>
        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            @endif
            @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        </div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('resetSelect2', () => {
                $('select').val(null).trigger('change');
            });
        });
    </script>
@endpush