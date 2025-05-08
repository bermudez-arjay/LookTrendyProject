<div>
    @if($open)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-max">
            <h2 class="text-xl font-semibold mb-4">Crear Cliente</h2>

            {{-- Formulario --}}
            <div class="grid grid-cols-4 md:grid-cols-4 gap-6">
                <input type="text" wire:model="Client_FirstName" placeholder="Nombre" class="w-full border px-4 py-2 rounded" />
                <input type="text" wire:model="Client_LastName" placeholder="Apellido" class="w-full border px-4 py-2 rounded" />
                <input type="text" wire:model="Client_Identity" placeholder="Identidad" class="w-full border px-4 py-2 rounded col-span-2" />
                <input type="email" wire:model="Client_Email" placeholder="Email" class="w-full border px-4 py-2 rounded col-span-2" />
                <input type="text" wire:model="Client_Phone" placeholder="Teléfono" class="w-full border px-4 py-2 rounded col-span-2" />
                <input type="text" wire:model="Client_Address" placeholder="Dirección" class="w-full border px-4 py-2 rounded col-span-4" />
            </div>

            {{-- Botones --}}
            <div class="mt-4 flex justify-end space-x-2">
                <button wire:click="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</button>
                <button wire:click="save2" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Registrar</button>
            </div>
        </div>
    </div>
    @if (session()->has('message'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 3000)" 
        x-show="show"
        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" 
        role="alert"
    >
        <strong class="font-bold">Éxito:</strong>
        <span class="block sm:inline">{{ session('message') }}</span>
    </div>
@endif
    @endif
</div>
