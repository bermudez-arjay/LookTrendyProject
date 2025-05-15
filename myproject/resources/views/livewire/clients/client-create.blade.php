<div>
    @if($open)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-max">
            <h2 class="text-xl font-semibold mb-4">Crear Cliente</h2>

            @if (session()->has('status'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                        {{ session('status') }}
                    </div>
            @endif


               @if (session()->has('error'))
                    <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

            {{-- Formulario --}}
            <div class="grid grid-cols-4 md:grid-cols-4 gap-6">

    <div class="flex flex-col col-span-1">
        <input type="text" wire:model="Client_FirstName" placeholder="Nombre" class="w-full border px-4 py-2 rounded" />
        @error('Client_FirstName') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="flex flex-col col-span-1">
        <input type="text" wire:model="Client_LastName" placeholder="Apellido" class="w-full border px-4 py-2 rounded" />
        @error('Client_LastName') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="flex flex-col col-span-2">
        <input type="text" wire:model="Client_Identity" placeholder="Identidad" class="w-full border px-4 py-2 rounded" />
        @error('Client_Identity') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="flex flex-col col-span-2">
        <input type="email" wire:model="Client_Email" placeholder="Email" class="w-full border px-4 py-2 rounded" />
        @error('Client_Email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="flex flex-col col-span-2">
        <input type="text" wire:model="Client_Phone" placeholder="Teléfono" class="w-full border px-4 py-2 rounded" />
        @error('Client_Phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="flex flex-col col-span-4">
        <input type="text" wire:model="Client_Address" placeholder="Dirección" class="w-full border px-4 py-2 rounded" />
        @error('Client_Address') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>
</div>


            {{-- Botones --}}
            <div class="mt-4 flex justify-end space-x-2">
                <button wire:click="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</button>
                <button wire:click="save2" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Registrar</button>
            </div>
        </div>
    </div>
    
    @endif
</div>
