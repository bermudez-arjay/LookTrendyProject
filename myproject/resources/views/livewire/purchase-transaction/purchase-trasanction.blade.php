<div class="p-6 bg-white shadow rounded-lg space-y-6">
    <h2 class="text-2xl font-bold mb-4">Registrar Compra</h2>

    @if (session()->has('success'))
        <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block mb-1 text-sm font-semibold">Usuario:</label>
            <input type="text" value="{{ Auth::user()->User_FirstName }}" class="w-full border-gray-300 rounded p-2" readonly>
            <input type="hidden" wire:model="selectedUserId">
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold">Proveedor:</label>
            <select wire:model="selectedSupplierId" class="w-full border-gray-300 rounded p-2">
                <option value="">Seleccionar proveedor</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->Supplier_ID }}">{{ $supplier->Supplier_Name }}</option>
                @endforeach
            </select>
            @error('selectedSupplierId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block mb-1 text-sm font-semibold">Producto:</label>
            <select wire:model="selectedProductId" class="w-full border-gray-300 rounded p-2">
                <option value="">Seleccionar producto</option>
                @foreach ($products as $product)
                    <option value="{{ $product->Product_ID }}">{{ $product->Product_Name }}</option>
                @endforeach
            </select>
            @error('selectedProductId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block mb-1 text-sm font-semibold">Cantidad:</label>
            <input type="number" wire:model="quantity" class="w-full border-gray-300 rounded p-2" min="1">
            @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block mb-1 text-sm font-semibold">Precio Unitario:</label>
            <input type="number" step="0.01" wire:model="unitPrice" class="w-full border-gray-300 rounded p-2">
            @error('unitPrice') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block mb-1 text-sm font-semibold">Impuesto (15%):</label>
            <input type="number" value="0.15" readonly class="w-full border-gray-300 rounded p-2 bg-gray-100 text-gray-700">
        </div>
    </div>

    <div>
        <button wire:click="addProduct" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Agregar Producto
        </button>
    </div>

    @if (!empty($productList))
        <div class="overflow-x-auto mt-6">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="py-2 px-4 border-b">Producto</th>
                        <th class="py-2 px-4 border-b">Cantidad</th>
                        <th class="py-2 px-4 border-b">Precio Unitario</th>
                        <th class="py-2 px-4 border-b">Subtotal</th>
                        <th class="py-2 px-4 border-b">Total con IVA</th>
                        <th class="py-2 px-4 border-b">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productList as $index => $item)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $item['name'] }}</td>
                            <td class="py-2 px-4 border-b">{{ $item['quantity'] }}</td>
                            <td class="py-2 px-4 border-b">${{ number_format($item['unit_price'], 2) }}</td>
                            <td class="py-2 px-4 border-b">${{ number_format($item['subtotal'], 2) }}</td>
                            <td class="py-2 px-4 border-b">${{ number_format($item['total_with_tax'], 2) }}</td>
                            <td class="py-2 px-4 border-b">
                                <button wire:click="removeProduct({{ $index }})" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="font-bold">
                        <td colspan="4" class="py-2 px-4 border-t text-right">Total General:</td>
                        <td class="py-2 px-4 border-t">${{ number_format(collect($productList)->sum('total_with_tax'), 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <button wire:click="saveTransaction" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Guardar Transacción
            </button>
        </div>
    @endif
</div>
