<div>
    @if($confirmingClientDeletion)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-lg font-semibold">
                    ¿Eliminar a {{ $clientToDelete->Client_FirstName }} {{ $clientToDelete->Client_LastName }}?
                </h2>
                <p class="mb-4 text-sm text-gray-600">Esta acción no se puede deshacer.</p>
                
                @if($errorMessage)
                    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        <p>{{ $errorMessage }}</p>
                    </div>
                @endif
                
                <div class="mt-4 flex justify-end">
                    <button wire:click="$set('confirmingClientDeletion', false)" 
                            class="ml-2 px-4 py-2 mr-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                        Cancelar
                    </button>

                    <button wire:click="deleteClient" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>Eliminar</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>