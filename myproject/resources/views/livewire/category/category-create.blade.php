<div>
    <div>
    @if($open)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Contenedor del modal -->
         <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300" aria-hidden="true"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Contenido del modal -->
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden transition-all duration-300 transform">
                <!-- Header -->
                <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Nueva Categoría</h3>
                            <p class="mt-1 text-sm text-gray-500">Complete los datos de la categoría</p>
                        </div>
                        <button 
                            wire:click="closeModal" 
                            class="text-gray-400 hover:text-gray-500 transition-colors p-1 rounded-full hover:bg-gray-50"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="px-6 py-4 space-y-4">
                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            wire:model.defer="Category_Name"
                            wire:keydown.enter="save"
                            placeholder="Ej: Electrónica"
                            class="block w-full rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2 text-sm @error('Category_Name') border-red-500 @enderror"
                        />
                        @error('Category_Name')
                            <p class="mt-1 text-xs text-red-500">{{$message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea
                            wire:model.defer="Category_Description"
                            placeholder="Ej: Productos electrónicos y dispositivos"
                            rows="3"
                            class="block w-full rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2 text-sm @error('Category_Description') border-red-500 @enderror"
                        ></textarea>
                        @error('Category_Description')
                            <p class="mt-1 text-xs text-red-500">{{$message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                    <button
                        type="button"
                        wire:click="closeModal"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors shadow-sm flex items-center justify-center min-w-24"
                    >
                        <span wire:loading.remove wire:target="save">Registrar Categoría</span>
                        <span wire:loading wire:target="save">
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
</div>
</div>
