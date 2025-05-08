<div>
    @if($open)
    
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-max">
            <h2 class="text-xl font-semibold mb-4">Editar Cliente</h2>

            {{-- Formulario --}}
            <div class="grid grid-cols-4 md:grid-cols-4 gap-6">
                <input type="text" wire:model.defer="Client_FirstName" placeholder="Nombre" class="w-full border px-4 py-2 rounded" />
                <input type="text" wire:model.defer="Client_LastName" placeholder="Apellido" class="w-full border px-4 py-2 rounded" />
                <input type="text" wire:model.defer="Client_Identity" placeholder="Identidad" class="w-full border px-4 py-2 rounded col-span-2" />
                <input type="email" wire:model.defer="Client_Email" placeholder="Email" class="w-full border px-4 py-2 rounded col-span-2" />
                <input type="text" wire:model.defer="Client_Phone" placeholder="Teléfono" class="w-full border px-4 py-2 rounded col-span-2" />
                <input type="text" wire:model.defer="Client_Address" placeholder="Dirección" class="w-full border px-4 py-2 rounded col-span-4" />
            </div>

            {{-- Botones --}}
            <div class="mt-4 flex justify-end space-x-2">
                <button wire:click="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</button>
                <button wire:click="update" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Actualizar</button>
            </div>
        </div>
    </div>
    
    @endif
</div>
