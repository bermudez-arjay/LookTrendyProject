<div>
    @if($open)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-2xl">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Crear Producto</h2>

            {{-- Formulario --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1">Nombre del producto</label>
                    <input type="text" wire:model.defer="Product_Name" placeholder="Ingrese el nombre" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1">Precio Unitario</label>
                    <input type="number" step="0.01" wire:model.defer="Unit_Price" placeholder="0.00" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div class="flex flex-col md:col-span-2">
                    <label class="text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select wire:model.defer="Category" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione una Categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col md:col-span-2">
                    <label class="text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea wire:model.defer="Description" rows="3" placeholder="Agregue una descripción..." class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex justify-end gap-3">
                <button wire:click="closeModal" class="px-5 py-2 rounded-lg bg-gray-500 text-white hover:bg-gray-600 transition">Cancelar</button>
                <button wire:click="save" class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">Crear</button>
            </div>
        </div>
    </div>
    @endif
</div>
