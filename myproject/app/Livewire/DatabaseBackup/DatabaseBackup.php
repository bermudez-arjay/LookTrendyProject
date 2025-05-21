<?php

namespace App\Livewire\DatabaseBackup;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseBackup extends Component
{
    use WithFileUploads;

    public $backupMessage = '';
    public $backups = [];
    public $restoreFile;
    public $databaseName;public $confirmingDelete = false;
public $fileToDelete = '';


    public $isProcessing = false;

    protected $rules = [
        'restoreFile' => 'required|file|mimes:sql,txt|max:102400' // 100MB max
    ];

    protected $messages = [
        'restoreFile.required' => 'Debe seleccionar un archivo de backup',
        'restoreFile.file' => 'El archivo seleccionado no es válido',
        'restoreFile.mimes' => 'El archivo debe ser de tipo SQL o TXT',
        'restoreFile.max' => 'El archivo no puede superar los 100MB'
    ];

    public function mount()
    {
        $this->authorizeAction();
        $this->databaseName = config('database.connections.mysql.database');
        $this->loadBackups();
    }

    protected function authorizeAction()
    {
     if (!Auth::check() || Auth::user()->User_Role !== 'Administrador') {
    abort(403, 'No autorizado para realizar esta acción');
}
   
    }

    public function loadBackups()
    {
        try {
            $files = Storage::disk('local')->files('backups/'.$this->databaseName);
            $this->backups = collect($files)
                ->sortByDesc(function($file) {
                    return Storage::lastModified($file);
                })
                ->values()
                ->all();
        } catch (\Exception $e) {
            $this->backupMessage = '❌ Error al cargar backups: '.$e->getMessage();
        }
    }

    public function confirmDelete($file)
{
    $this->fileToDelete = $file;
    $this->confirmingDelete = true;
}

     public function backupDatabase()
    {
        $dbName = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $dir = storage_path("app/backups/{$dbName}");
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $backupFile = "{$dir}/{$dbName}_" . date('Y-m-d_H-i-s') . ".sql";
        $command = "mysqldump -h {$host} -u {$username} " . ($password ? "-p{$password} " : "") . "{$dbName} > {$backupFile}";

        exec($command, $output, $result);

        if ($result === 0) {
            $this->backupMessage = '✅ Backup creado correctamente.';
            $this->loadBackups();
        } else {
            $this->backupMessage = '❌ Error al crear backup.';
        }
    }

       public function restoreDatabase()
    {
        $this->validate([
            'restoreFile' => 'required|file|mimes:sql,txt'
        ]);

        $path = $this->restoreFile->storeAs('backups/temp', $this->restoreFile->getClientOriginalName());
        $fullPath = storage_path("app/{$path}");

        $dbName = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $command = "mysql -h {$host} -u {$username} " . ($password ? "-p{$password} " : "") . "{$dbName} < {$fullPath}";

        exec($command, $output, $result);

        if ($result === 0) {
            $this->backupMessage = '✅ Base de datos restaurada correctamente.';
        } else {
            $this->backupMessage = '❌ Error al restaurar la base de datos.';
        }
    }
    public function downloadBackup($file)
    {
        $this->authorizeAction();
        
        if (!Storage::exists($file)) {
            $this->backupMessage = '❌ El archivo no existe';
            return;
        }

        if (!str_contains($file, $this->databaseName)) {
            abort(403, 'Archivo no permitido');
        }

        return Storage::download($file);
    }

    public function deleteBackup($file)
    {
        $this->authorizeAction();
        
        if (!Storage::exists($file)) {
            $this->backupMessage = '❌ El archivo no existe';
            return;
        }

        if (!str_contains($file, $this->databaseName)) {
            $this->backupMessage = '❌ Error: archivo no permitido';
            return;
        }

        try {
            Storage::delete($file);
            $this->backupMessage = '🗑️ Backup eliminado: '.basename($file);
            $this->confirmingDelete = false;
            $this->loadBackups();
        } catch (\Exception $e) {
            $this->backupMessage = '❌ Error al eliminar: '.$e->getMessage();
        }
    }
    public function deleteConfirmed()
{
    $this->deleteBackup($this->fileToDelete);
    $this->confirmingDelete = false;
    $this->fileToDelete = '';
}
public function cancelDelete()
{
    $this->confirmingDelete = false;
    $this->fileToDelete = '';
}


    public function render()
    {
        return view('livewire.database-backup.database-backup')->layout('layouts.app');
    }
}