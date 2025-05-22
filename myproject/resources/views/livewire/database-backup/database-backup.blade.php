<div class="fixed inset-0 bg-gray-50 p-4 overflow-auto">
    <div class="max-w-[100%] mx-auto h-full flex flex-col">
        <!-- Encabezado -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">📦 Backup y Restore de Base de Datos</h2>
            @error('auth') 
                <div class="mt-2 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                    <p>{{ $message }}</p>
                </div>
            @enderror
        </div>

        <!-- Mensaje de estado -->
        @if($backupMessage)
            <div class="mb-4 p-4 rounded-lg 
                {{ str_contains($backupMessage, '✅') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $backupMessage }}
            </div>
        @endif

        <!-- Sección de acciones -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 flex-shrink-0">
            <!-- Crear Backup -->
            <div class="bg-white p-6 rounded-xl shadow border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">🔄 Crear nuevo backup</h3>
                <p class="text-sm text-gray-500 mb-4">Crea una copia de seguridad completa de la base de datos actual</p>
                <div class="flex items-center space-x-4">
                    <button 
                        wire:click="backupDatabase"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                        @if($isProcessing) disabled @endif
                    >
                        <span wire:loading.remove>Crear Backup</span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin mr-2"></i> Procesando...
                        </span>
                    </button>
                </div>
                @error('backup')
                    <p class="mt-2 text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Restaurar Backup -->
            <div class="bg-white p-6 rounded-xl shadow border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">♻️ Restaurar desde archivo</h3>
                <p class="text-sm text-gray-500 mb-4">Seleccione un archivo de backup para restaurar la base de datos</p>
                <form wire:submit.prevent="restoreDatabase" class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <input 
                            type="file" 
                            wire:model="restoreFile"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2"
                            accept=".sql,.txt"
                            @if($isProcessing) disabled @endif
                        >
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-lg whitespace-nowrap"
                            @if($isProcessing) disabled @endif
                        >
                            <span wire:loading.remove>Restaurar Backup</span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-2"></i> Procesando...
                            </span>
                        </button>
                    </div>
                    @error('restoreFile')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </form>
            </div>
        </div>

        <!-- Tabla de backups -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-6 flex-grow flex flex-col">
            <h3 class="text-xl font-semibold text-gray-700 mb-3">📁 Backups disponibles</h3>
            <p class="text-sm text-gray-500 mb-4">Listado de copias de seguridad disponibles para descargar o restaurar</p>
            
            <div class="flex-grow overflow-hidden flex flex-col">
                @if(count($backups) > 0)
                    <div class="overflow-y-auto flex-grow">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombre del Archivo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tamaño
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($backups as $file)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">
                                            {{ basename($file) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format(Storage::size($file) / 1024 / 1024, 2) }} MB
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ date('Y-m-d H:i', Storage::lastModified($file)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap flex space-x-4">
                                            <button 
                                                wire:click="downloadBackup('{{ $file }}')"
                                                wire:loading.attr="disabled"
                                                class="hover:bg-white-600 text-green-500 px-4 py-2 text-sm rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-lg flex items-center space-x-2"
                                                title="Descargar backup"
                                            >
                                                <i class="fas fa-download"></i>
                                                <span>Descargar</span>
                                            </button>
                                            <button 
                                                wire:click="confirmDelete('{{ $file }}')"
                                                wire:loading.attr="disabled"
                                                class="hover:bg-white-600 text-red-500 px-4 py-2 text-sm rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-lg flex items-center space-x-2"
                                                title="Eliminar backup"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                                <span>Eliminar</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex-grow flex items-center justify-center text-gray-500">
                        No hay backups disponibles
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    @if($confirmingDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar eliminación</h3>
                <p class="text-sm text-gray-500 mb-6">¿Estás seguro que deseas eliminar el backup "{{ basename($fileToDelete) }}"?</p>
                <div class="flex justify-end space-x-4">
                    <button 
                        wire:click="cancelDelete"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                    >
                        Cancelar
                    </button>
                    <button 
                        wire:click="deleteBackup('{{ $fileToDelete }}')"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        // Confirmación antes de restaurar
        Livewire.on('confirmRestore', (file) => {
            if (!confirm(`¿Estás seguro que deseas restaurar la base de datos desde ${file}? Esta acción no se puede deshacer.`)) {
                return;
            }
            @this.call('restoreDatabase', file);
        });
    });
</script>
@endpush