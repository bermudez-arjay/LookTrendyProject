<div id="form" class="max-w-full px-8 mx-auto p-6 bg-white rounded-xl shadow-lg border border-gray-200">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">Crear Nuevo Crédito</h2>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Sección Cliente y Plazo -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- USUARIO -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Usuario</label>
                <input type="text" value="{{ Auth::user()->User_FirstName }}"
                    class="mt-1 block w-full rounded-lg bg-gray-50 border-gray-300 shadow-sm py-2 px-3 border" readonly>
                <input type="hidden" wire:model="selectedUserId">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Cliente <span
                        class="text-red-500">*</span></label>
                <select wire:model.live="client_id" id="client_id"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                    <option value="">Seleccione un cliente</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->Client_ID }}">{{ $client->Client_FirstName }}
                            {{ $client->Client_LastName }}
                        </option>
                    @endforeach
                </select>
                @error('client_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Plazo -->

        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sección Tipo de Pago -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Tipo de Pago <span
                        class="text-red-500">*</span></label>
                <select wire:model.live="payment_type_id" id="payment_type_id"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                    <option value="">Seleccione tipo de pago</option>
                    @foreach($paymentTypes as $type)
                        <option value="{{ $type->Payment_Type_ID }}">{{ $type->Payment_Type_Name }}</option>
                    @endforeach
                </select>
                @error('payment_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Plazo (Meses) <span
                        class="text-red-500">*</span></label>
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
                <label class="block text-sm font-medium text-gray-700">Fecha de Inicio <span
                        class="text-red-500">*</span></label>
                <input type="date" id="start_date" wire:model.live="start_date" readonly
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Fecha de Vencimiento -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Fecha de Vencimiento</label>
                <input type="date" id="due_date" value="{{ $due_date }}" readonly
                    class="mt-1 block w-full rounded-lg bg-gray-50 border-gray-300 shadow-sm py-2 px-3 border">
            </div>
        </div>
        <!-- Sección Productos -->
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-800">Productos</h3>
                <button type="button" wire:click="$set('showProductModal', true)"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Agregar Producto
                </button>
            </div>

            @if(count($creditDetails))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    IVA</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($creditDetails as $index => $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail['product_name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail['quantity'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($detail['subtotal'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($detail['vat'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${{ number_format($detail['total_with_vat'], 2) }}</td>
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
                <button type="submit" onclick="
                            document.getElementById('client_id').value ='';
                            document.getElementById('term').innerHTML ='';
                            document.getElementById('payment_type_id').innerHTML ='';
                            document.getElementById('start_date').value ='';
                             document.getElementById('due_date').value ='';
                            "
                    class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Guardar Crédito
                </button>
            </div>
        </div>
    </form>

    <!-- Modal de Producto -->
    @if($showProductModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Fondo -->
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

            <!-- Contenido del modal -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-4xl overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Seleccionar Producto</h3>
                                <p class="mt-1 text-sm text-gray-500">Busque y seleccione productos para agregar</p>
                            </div>
                            <button wire:click="$set('showProductModal', false)" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Barra de búsqueda -->
                        <div class="mt-4 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="searchProduct"
                                placeholder="Buscar productos..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Tabla de productos -->
                    <div class="px-6 pb-4">
                        <div class="overflow-y-auto max-h-96">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Producto</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stock</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Precio</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cantidad</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acción</th>
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
            {{ ($product['inventories']['Current_Stock'] ?? 0) > 10 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
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
            class="w-20 px-2 py-1 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            {{ ($product['inventories']['Current_Stock'] ?? 0) <= 0 ? 'disabled' : '' }}
        >
          @error('quantities.' . $product['Product_ID'])
            <span class="text-red-500 text-sm block mt-1">{{ $message }}</span>
        @enderror
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    <button 
        wire:click="addDetail({{ $product['Product_ID'] }})"
        class="text-indigo-600 hover:text-indigo-900"
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            window.addEventListener('credit-notify', event => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: event.detail.type || 'success',
                    title: event.detail.title || '¡Operación exitosa!',
                    text: event.detail.message || ''
                });
            });
        </script>
    @endpush
</div>