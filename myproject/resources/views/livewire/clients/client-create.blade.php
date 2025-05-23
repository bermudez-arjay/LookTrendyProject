<div>
    @if($open)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Contenedor del modal -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Contenido del modal -->
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden transition-all duration-300 transform">
                <!-- Header -->
                <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Nuevo Cliente</h3>
                            <p class="mt-1 text-sm text-gray-500">Complete los datos del cliente</p>
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
                    <!-- Nombre y Apellido -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                wire:model.defer="Client_FirstName"
                                wire:keydown.enter="save"
                                placeholder="Ej: Juan"
                                class="block w-full rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2 text-sm @error('Client_FirstName') border-red-500 @enderror"
                            />
                            @error('Client_FirstName')
                                <p class="mt-1 text-xs text-red-500">{{$message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                wire:model.defer="Client_LastName"
                                wire:keydown.enter="save"
                                placeholder="Ej: Pérez"
                                class="block w-full rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2 text-sm @error('Client_LastName') border-red-500 @enderror"
                            />
                            @error('Client_LastName')
                                <p class="mt-1 text-xs text-red-500">{{$message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Identidad -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Identidad <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            wire:model.defer="Client_Identity"
                            wire:keydown.enter="save"
                            placeholder="Ej: 001-123456-1234A"
                            x-mask="999-999999-9999a"
                            class="block w-full rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2 text-sm @error('Client_Identity') border-red-500 @enderror"
                        />
                        @error('Client_Identity')
                            <p class="mt-1 text-xs text-red-500">{{$message }}</p>
                        @enderror
                    </div>

                    <!-- Email y Teléfono -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input
                                type="email"
                                wire:model.defer="Client_Email"
                                wire:keydown.enter="save"
                                placeholder="Ej: cliente@example.com"
                                class="block w-full rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2 text-sm @error('Client_Email') border-red-500 @enderror"
                            />
                            @error('Client_Email')
                                <p class="mt-1 text-xs text-red-500">{{$message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono <span class="text-red-500">*</span></label>
                            <input
                                type="tel"
                                wire:model.defer="Client_Phone"
                                wire:keydown.enter="save"
                                placeholder="Ej: 88888888"
                                x-mask="99999999"
                                class="block w-full rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2 text-sm @error('Client_Phone') border-red-500 @enderror"
                            />
                            @error('Client_Phone')
                                <p class="mt-1 text-xs text-red-500">{{$message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dirección <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            wire:model.defer="Client_Address"
                            wire:keydown.enter="save"
                            placeholder="Ej: Residencial X, casa 123"
                            class="block w-full rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2 text-sm @error('Client_Address') border-red-500 @enderror"
                        />
                        @error('Client_Address')
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
                        <span wire:loading.remove wire:target="save">Registrar Cliente</span>
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