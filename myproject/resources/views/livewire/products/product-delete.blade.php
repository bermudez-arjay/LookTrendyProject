<div>
    <div>
        <!-- Modal de eliminación -->
        @if ($confirmingProductDeletion)
            <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-lg font-semibold">
                        ¿Estás seguro de que deseas eliminar a {{ $productToDelete->Product_Name }}?
                    </h2>
                    
                    @if($hasInventory)
                        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                            Este producto tiene stock en inventario y no se puede eliminar.
                        </div>
                    @else
                        <p class="mb-4 text-sm text-gray-600">Esta acción no se puede deshacer.</p>
                    @endif
                    
                    <div class="mt-4 flex justify-end">
                        <button wire:click="$set('confirmingProductDeletion', false)" class="ml-2 px-4 py-2 mr-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancelar</button>

                        <button wire:click="deleteProduct" 
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                                @if($hasInventory) disabled @endif>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>