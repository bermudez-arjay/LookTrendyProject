<div>
    @if($open)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-max">
            <h2 class="text-xl font-semibold mb-4">Crear Usuario</h2>

            {{-- Formulario --}}
            <div class="grid grid-cols-4 md:grid-cols-4 gap-6">
                <input type="text" wire:model.defer="User_FirstName" placeholder="Nombre" class="w-full border px-4 py-2 rounded" />
                <input type="text" wire:model.defer="User_LastName" placeholder="Apellido" class="w-full border px-4 py-2 rounded" />
                <select wire:model.defer="User_Role" class="w-full border px-4 py-2 rounded col-span-2">
                    <option value="">Seleccione un Rol</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol }}">{{ $rol }}</option>
                    @endforeach
                </select>
                <input type="email" wire:model.defer="User_Email" placeholder="Email" class="w-full border px-4 py-2 rounded col-span-2" />
                <input type="password" wire:model.defer="Password" placeholder="Contraseña" class="w-full border px-4 py-2 rounded col-span-2" />
                <input type="text" wire:model.defer="User_Phone" placeholder="Teléfono" class="w-full border px-4 py-2 rounded col-span-2" />

                <input type="text" wire:model.defer="User_Address" placeholder="Dirección" class="w-full border px-4 py-2 rounded col-span-2" />
                
            </div>

            {{-- Botones --}}
            <div class="mt-4 flex justify-end space-x-2">
                <button wire:click="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</button>
                <button wire:click="save" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Registrar</button>
            </div>
        </div>
    </div>
    @endif
</div>
