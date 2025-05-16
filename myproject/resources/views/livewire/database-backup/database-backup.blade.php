<div class="fixed inset-0 bg-gray-50 p-4 overflow-auto">
    <div class="max-w-[100%] mx-auto h-full flex flex-col">
        <!-- Encabezado -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">📦 Backup y Restore de Base de Datos</h2>
        </div>

        <!-- Sección de acciones -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 flex-shrink-0">
            <!-- Crear Backup -->
            <div class="bg-white p-6 rounded-xl shadow border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">🔄 Crear nuevo backup</h3>
                <p class="text-sm text-gray-500 mb-4">Crea una copia de seguridad completa de la base de datos actual</p>
                <div class="flex items-center space-x-4">
                    <button wire:click="backupDatabase"
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                        Crear Backup
                    </button>
                    @if ($backupMessage)
                        <p class="text-green-600 text-sm">{{ $backupMessage }}</p>
                    @endif
                </div>
            </div>

            <!-- Restaurar Backup -->
            <div class="bg-white p-6 rounded-xl shadow border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">♻️ Restaurar desde archivo</h3>
                <p class="text-sm text-gray-500 mb-4">Seleccione un archivo de backup para restaurar la base de datos</p>
                <form wire:submit.prevent="restoreDatabase" class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <input type="file" wire:model="restoreFile"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2">
                        <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-lg whitespace-nowrap">
                            Restaurar Backup
                        </button>
                    </div>
                    @error('restoreFile')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </form>
            </div>
        </div>

        <!-- Tabla de backups (ocupa el resto del espacio) -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-6 flex-grow flex flex-col">
            <h3 class="text-xl font-semibold text-gray-700 mb-3">📁 Backups disponibles</h3>
            <p class="text-sm text-gray-500 mb-4">Listado de copias de seguridad disponibles para descargar o restaurar</p>
            
            <div class="flex-grow overflow-hidden flex flex-col">
                <div class="overflow-y-auto flex-grow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre del Archivo
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
                                   <td class="px-6 py-4 whitespace-nowrap flex space-x-4">
    <button wire:click="downloadBackup('{{ $file }}')"
        class=" hover:bg-white-600 text-green-500 px-4 py-2 text-sm rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-lg flex items-center space-x-2">
        <i class="fas fa-download"></i>
        <span>Descargar</span>
    </button>
    <button wire:click="deleteBackup('{{ $file }}')"
        class=" hover:bg-white-600 text-red-500 px-4 py-2 text-sm rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-lg flex items-center space-x-2">
        <i class="fas fa-trash-alt"></i>
        <span>Eliminar</span>
    </button>
</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                @if($backups instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-4 border-t border-gray-200 pt-4">
                        {{ $backups->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>