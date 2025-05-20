<div>
    @if($open)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Fondo difuminado -->
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300" aria-hidden="true"></div>

        <!-- Contenedor del modal -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Contenido del modal -->
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transition-all duration-300 transform">
                <!-- Header -->
                <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Crear Usuario</h3>
                            <p class="mt-1 text-sm text-gray-500">Complete los datos del nuevo usuario</p>
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

                <!-- Mensajes -->
                @if (session()->has('message'))
                <div class="bg-emerald-50 px-6 py-3">
                    <div class="flex items-center text-emerald-600 text-sm">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ session('message') }}
                    </div>
                </div>
                @endif

                @if (session()->has('error'))
                <div class="bg-rose-50 px-6 py-3">
                    <div class="flex items-center text-rose-600 text-sm">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
                @endif

                <!-- Formulario -->
                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    wire:model.defer="User_FirstName"
                                    placeholder="Ej: Juan"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                                />
                                @error('User_FirstName')
                                    <p class="mt-1 text-xs text-rose-500">{{$message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    wire:model.defer="User_LastName"
                                    placeholder="Ej: Pérez"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                                />
                                @error('User_LastName')
                                    <p class="mt-1 text-xs text-rose-500">{{$message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="relative">
                                <input
                                    type="email"
                                    wire:model.defer="User_Email"
                                    placeholder="Ej: usuario@ejemplo.com"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                                />
                                @error('User_Email')
                                    <p class="mt-1 text-xs text-rose-500">{{$message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    wire:model.defer="User_Phone"
                                    placeholder="Ej: 3001234567"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                                />
                                @error('User_Phone')
                                    <p class="mt-1 text-xs text-rose-500">{{$message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                            <div class="relative">
                                <input
                                    type="password"
                                    wire:model.defer="Password"
                                    placeholder="••••••••"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                                />
                                @error('Password')
                                    <p class="mt-1 text-xs text-rose-500">{{$message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres, incluyendo mayúsculas, números y caracteres especiales</p>
                            </div>
                        </div>

                        <!-- Rol -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                            <select
                                wire:model.defer="User_Role"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                            >
                                <option value="">Seleccione un rol</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol }}">{{ $rol }}</option>
                                @endforeach
                            </select>
                            @error('User_Role')
                                <p class="mt-1 text-xs text-rose-500">{{$message }}</p>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    wire:model.defer="User_Address"
                                    placeholder="Ej: Calle 123 #45-67"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2.5 text-sm"
                                />
                                @error('User_Address')
                                    <p class="mt-1 text-xs text-rose-500">{{$message }}</p>
                                @enderror
                            </div>
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
                        wire:click="save"
                        class="px-4 py-2.5 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors shadow-sm"
                    >
                        Registrar Usuario
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>