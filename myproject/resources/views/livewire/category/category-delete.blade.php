<div>
    <!-- Modal de eliminación -->
    @if ($confirmingCategoryDeletion)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-lg font-semibold">
                    ¿Eliminar categoría {{ $categoryToDelete->Category_Name }}?
                </h2>
                
                @if($hasProductsWithStock)
                    <div class="my-4 p-3 bg-red-100 text-red-700 rounded text-sm">
                        Esta categoría contiene productos con stock y no se puede eliminar.
                    </div>
                @else
                    <p class="my-4 text-sm text-gray-600">
                        Todos los productos asociados también serán marcados como inactivos.
                    </p>
                @endif
                
                <div class="mt-4 flex justify-end space-x-3">
                    <button 
                        wire:click="$set('confirmingCategoryDeletion', false)" 
                        class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500"
                    >
                        Cancelar
                    </button>

                    <button 
                        wire:click="deleteCategory" 
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                        @if($hasProductsWithStock) disabled @endif
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>