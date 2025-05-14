<div class="max-w-full px-8 mx-auto p-6 bg-white rounded-xl shadow-lg border border-gray-200">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">Registrar Compra</h2>

    @if (session()->has('success'))
        <div class="p-3 bg-green-100 text-green-800 rounded-lg mb-4">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="saveTransaction" class="space-y-6">
        <!-- Sección Usuario, Proveedor y Fecha -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Usuario -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Usuario</label>
                <input type="text" value="{{ Auth::user()->User_FirstName }}"
                    class="mt-1 block w-full rounded-lg bg-gray-50 border-gray-300 shadow-sm py-2 px-3 border" readonly>
                <input type="hidden" wire:model="selectedUserId">
            </div>

            <!-- Proveedor -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Proveedor <span
                        class="text-red-500">*</span></label>
                <select wire:model="selectedSupplierId"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                    <option value="">Seleccionar proveedor</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->Supplier_ID }}">{{ $supplier->Supplier_Name }}</option>
                    @endforeach
                </select>
                @error('selectedSupplierId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Fecha -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Fecha</label>
                <input type="date" value="{{ date('Y-m-d') }}" disabled
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

            <!-- Lista de Productos -->
            @if(count($productList))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio Unitario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total con IVA</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($productList as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['quantity'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($item['unit_price'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($item['subtotal'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${{ number_format($item['total_with_tax'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button type="button" wire:click="removeProduct({{ $index }})"
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
                <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                <input type="text" value="${{ number_format(collect($productList)->sum('subtotal'), 2) }}" readonly
                    class="mt-1 block w-full rounded-lg bg-white border-gray-300 shadow-sm py-2 px-3 border font-medium text-gray-900">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Total con IVA</label>
                <input type="text" value="${{ number_format(collect($productList)->sum('total_with_tax'), 2) }}"
                    readonly
                    class="mt-1 block w-full rounded-lg bg-white border-gray-300 shadow-sm py-2 px-3 border font-medium text-gray-900">
            </div>

            <div class="flex items-end">
                <button type="submit"
                    class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Guardar Compra
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Producto -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Producto <span
                                    class="text-red-500">*</span></label>
                            <select wire:model.live="selectedProductId"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                                <option value="">Seleccionar producto</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->Product_ID }}">{{ $product->Product_Name }}</option>
                                @endforeach
                            </select>
                            {{-- @error('selectedProductId') <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror --}}
                        </div>

                        <!-- Cantidad -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Cantidad <span
                                    class="text-red-500">*</span></label>
                            <input type="number" wire:model="quantity" min="1"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                        </div>

                        <!-- Precio Unitario -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Precio Unitario <span
                                    class="text-red-500">*</span></label>
                            <input type="number" step="0.01" wire:model="unitPrice"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                        </div>

                        <!-- Tipo de Pago -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Tipo de Pago <span
                                    class="text-red-500">*</span></label>
                            <select wire:model="SelectpaymentsTypes"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                                <option value="">Seleccione tipo de pago</option>
                                @foreach($paymentsType as $type)
                                    <option value="{{ $type->Payment_Type_ID }}">{{ $type->Payment_Type_Name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Impuesto -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Impuesto</label>
                            <input type="number" value="0.15" readonly
                                class="mt-1 block w-full rounded-lg bg-gray-50 border-gray-300 shadow-sm py-2 px-3 border">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button wire:click="$set('showProductModal', false)"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </button>
                        <button wire:click="addProduct"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
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
    <style>
        /* Estilo personalizado para el scroll */
        .max-h-96 {
            max-height: 24rem;
            /* 96px = 24rem */
        }

        /* Estilo para mantener visibles los encabezados y totales al hacer scroll */
        thead.sticky th {
            position: sticky;
            top: 0;
            background-color: #f9fafb;
            /* bg-gray-50 */
            z-index: 10;
        }

        tr.sticky td {
            position: sticky;
            bottom: 0;
            background-color: #f9fafb;
            /* bg-gray-50 */
            z-index: 10;
        }
    </style>
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