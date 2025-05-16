<?php

namespace App\Livewire\DatabaseBackup;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup extends Component
{
    use WithFileUploads;

    public $backupMessage = '';
    public $backups = [];
    public $restoreFile;
    public $databaseName;
    public $backupPath = '';

    public function mount()
    {
        $this->databaseName = config('database.connections.mysql.database');
        $this->loadBackups();
    }

    public function loadBackups()
    {
        $files = Storage::disk('local')->files('backups/'.$this->databaseName);
        $this->backups = array_reverse($files);
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

    public function updatedRestoreFile()
    {
        $this->validate([
            'restoreFile' => 'required|file|mimes:sql,txt'
        ]);
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
        if (!str_contains($file, $this->databaseName)) {
            abort(403, 'Archivo no permitido');
        }

        return response()->download(storage_path('app/' . $file));
    }

    public function deleteBackup($file)
    {
        if (!str_contains($file, $this->databaseName)) {
            $this->backupMessage = '❌ Error: archivo no permitido.';
            return;
        }

        Storage::disk('local')->delete($file);
        $this->backupMessage = '🗑️ Backup eliminado: '.basename($file);
        $this->loadBackups();
    }

    public function selectBackupPath()
    {
        $this->dispatch('open-file-dialog',
            type: 'save',
            accept: '.sql',
            defaultName: $this->databaseName.'-backup-'.date('Y-m-d-H-i-s').'.sql'
        );
    }

    public function render()
    {
        return view('livewire.database-backup.database-backup')->layout('layouts.app');
    }
}
