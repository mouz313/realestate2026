<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupRun extends Command
{
    protected $signature = 'backup:run {--dest= : Optional destination directory}';
    protected $description = 'Create a backup snapshot of the database and public uploads';

    public function handle(): int
    {
        $destDir = $this->option('dest') ?: storage_path('app/backups');
        if (! File::isDirectory($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }

        $timestamp = now()->format('Y-m-d-His');
        $zipPath = $destDir.'/backup-'.$timestamp.'.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error('Could not create zip archive.');

            return self::FAILURE;
        }

        $connection = config('database.default');
        $driver = config('database.connections.'.$connection.'.driver');

        if ($driver === 'sqlite') {
            $dbPath = config('database.connections.'.$connection.'.database');
            if ($dbPath && File::exists($dbPath)) {
                $zip->addFile($dbPath, 'database.sqlite');
            }
        }

        $publicDisk = storage_path('app/public');
        if (File::isDirectory($publicDisk)) {
            foreach (File::allFiles($publicDisk) as $file) {
                $zip->addFile($file->getRealPath(), 'storage/'.str_replace('\\', '/', $file->getRelativePathname($publicDisk)));
            }
        }

        $zip->addFromString('backup-meta.json', json_encode([
            'date' => now()->toISOString(),
            'database' => $connection,
            'driver' => $driver,
        ]));
        $zip->close();

        $this->info('Backup created: '.$zipPath);

        return self::SUCCESS;
    }
}
