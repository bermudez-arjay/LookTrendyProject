<div>
    @if($open)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Fondo difuminado -->
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300" aria-hidden="true"></div>

        <!-- Contenedor del modal -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Contenido del modal -->
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transition-all duration-300 transform">
                <!-- Header -->
                <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Editar Proveedor</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $Supplier_Name }}</p>
                        </div>
                        <button 
                            wire:click="closeModal" 
                            class="text-gray-400 hover:text-gray-500 transition-colors p-1 rounded-full hover:bg-gray-50"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="px-6 py-4 space-y-4">
                    <!-- Identificación -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Identificación</label>
                        <div class="relative">
                            <input
                                type="text"
                                wire:model.defer="Supplier_Identity"
                                placeholder="001-123456-1234A"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                            />
                            @error('Supplier_Identity')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Nombre del Proveedor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Proveedor</label>
                        <div class="relative">
                            <input
                                type="text"
                                wire:model.defer="Supplier_Name"
                                placeholder="Nombre completo"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                            />
                            @error('Supplier_Name')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <input
                                type="email"
                                wire:model.defer="Supplier_Email"
                                placeholder="correo@proveedor.com"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                            />
                            @error('Supplier_Email')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- RUC -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RUC</label>
                        <div class="relative">
                            <input
                                type="text"
                                wire:model.defer="Supplier_RUC"
                                placeholder="Número de RUC"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                            />
                            @error('Supplier_RUC')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <div class="relative">
                            <input
                                type="text"
                                wire:model.defer="Supplier_Phone"
                                placeholder="81234567"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                            />
                            @error('Supplier_Phone')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">8 dígitos, comenzando con 2, 5, 7 u 8</p>
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                        <div class="relative">
                            <input
                                type="text"
                                wire:model.defer="Supplier_Address"
                                placeholder="Dirección completa"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                            />
                            @error('Supplier_Address')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                    <button
                        wire:click="closeModal"
                        class="px-4 py-2.5 text-sm font-medium rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        wire:click="update"
                        class="px-4 py-2.5 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors shadow-sm"
                    >
                        Actualizar Proveedor
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>