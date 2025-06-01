<div>
<div>
    <!-- Modal de confirmación para eliminar -->
    @if($confirmingCategoryDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Fondo oscuro -->
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300"></div>

            <!-- Contenedor del modal -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden transition-all duration-300 transform">
                    <!-- Header -->
                    <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Confirmar eliminación</h3>
                                <p class="mt-1 text-sm text-gray-500">Esta acción no se puede deshacer</p>
                            </div>
                            <button 
                                wire:click="$set('confirmingCategoryDeletion', false)" 
                                class="text-gray-400 hover:text-gray-500 transition-colors p-1 rounded-full hover:bg-gray-50"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="px-6 py-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 pt-0.5">
                                <svg class="h-10 w-10 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">¿Eliminar categoría?</h4>
                                <p class="mt-1 text-sm text-gray-500">
                                    Estás a punto de eliminar la categoría: 
                                    <span class="font-semibold">{{ $categoryToDelete->Category_Name ?? '' }}</span>
                                </p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Todos los productos asociados a esta categoría se marcarán como inactivos.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                        <button
                            wire:click="$set('confirmingCategoryDeletion', false)"
                            class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            wire:click="deleteCategory"
                            class="px-4 py-2 text-sm font-medium rounded-lg bg-rose-600 text-white hover:bg-rose-700 transition-colors shadow-sm flex items-center justify-center min-w-24"
                        >
                            <span wire:loading.remove wire:target="deleteCategory">Eliminar</span>
                            <span wire:loading wire:target="deleteCategory">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div></div>
