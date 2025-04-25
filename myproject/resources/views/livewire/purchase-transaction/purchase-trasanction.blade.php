<div>
    <div class="max-w-6xl mx-auto p-6 bg-white rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-4">Registrar Compra</h2>
    
        {{-- Mensajes --}}
        @error('form') <div class="text-red-600 mb-4">{{ $message }}</div> @enderror
        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                {{ session('message') }}
            </div>
        @endif
    
        {{-- Selects de Usuario y Proveedor --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-semibold mb-1">Usuario</label>
                <select wire:model="user_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Selecciona --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->User_ID }}">{{ $user->User_FirstName }} {{ $user->User_LastName }}</option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
    
            <div>
                <label class="block text-sm font-semibold mb-1">Proveedor</label>
                <select wire:model="supplier_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Selecciona --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->Supplier_ID }}">{{ $supplier->Supplier_Name }}</option>
                    @endforeach
                </select>
                @error('supplier_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
    
        {{-- Agregar Producto --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Producto</label>
                <select wire:model="product_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Selecciona --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->Product_ID }}">{{ $product->Product_Name }}</option>
                    @endforeach
                </select>
            </div>
    
            <div>
                <label class="block text-sm font-semibold mb-1">Cantidad</label>
                <input type="number" wire:model="quantity" class="w-full border rounded px-3 py-2" min="1">
            </div>
    
            <div>
                <label class="block text-sm font-semibold mb-1">Precio Unitario</label>
                <input type="number" wire:model="unit_price" class="w-full border rounded px-3 py-2" step="0.01">
            </div>
        </div>
    
        <div class="mb-6">
            <button wire:click="addProduct"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Agregar Producto
            </button>
        </div>
    
        {{-- Tabla de Productos Agregados --}}
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded">
                <thead class="bg-gray-100 text-gray-700 text-left">
                    <tr>
                        <th class="px-4 py-2 border">Producto</th>
                        <th class="px-4 py-2 border">Cantidad</th>
                        <th class="px-4 py-2 border">Precio Unitario</th>
                        <th class="px-4 py-2 border">Subtotal</th>
                        <th class="px-4 py-2 border">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $item)
                        <tr>
                            <td class="px-4 py-2 border">{{ $item['name'] }}</td>
                            <td class="px-4 py-2 border">{{ $item['quantity'] }}</td>
                            <td class="px-4 py-2 border">S/. {{ number_format($item['unit_price'], 2) }}</td>
                            <td class="px-4 py-2 border">S/. {{ number_format($item['subtotal'], 2) }}</td>
                            <td class="px-4 py-2 border">
                                <button wire:click="removeProduct({{ $index }})"
                                    class="text-red-600 hover:underline">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">No se han agregado productos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    
        {{-- Botón Guardar --}}
        <div class="mt-6 text-right">
            <button wire:click="save"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded">
                Guardar Compra
            </button>
        </div>
    </div>
        
</div>
