<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseBackup extends Command
{
    protected $signature   = 'backup:database';
    protected $description = 'Create a compressed database backup and clean old backups';

    /** Number of most-recent backups to keep; older files are deleted */
    private const BACKUP_RETENTION_COUNT = 7;

    public function handle(): int
    {
        $db        = config('database.connections.mysql');
        $timestamp = now()->format('Y-m-d-His');
        $filename  = "prolabios-{$timestamp}.sql";
        $tempPath  = storage_path("app/backup/{$filename}");

        // Ensure backup directory exists
        if (! is_dir(storage_path('app/backup'))) {
            mkdir(storage_path('app/backup'), 0755, true);
        }

        // Build mysqldump command
        $command = sprintf(
            'mysqldump --single-transaction --quick --lock-tables=false -h%s -u%s -p%s %s > %s 2>/dev/null',
            escapeshellarg($db['host'] ?? '127.0.0.1'),
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['database']),
            escapeshellarg($tempPath)
        );

        exec($command, $output, $exitCode);

        if ($exitCode !== 0 || ! file_exists($tempPath) || filesize($tempPath) === 0) {
            $this->error('Database backup failed!');
            \Log::channel('backup')->error('Database backup failed', [
                'exit_code' => $exitCode,
                'timestamp' => now(),
            ]);

            return 1;
        }

        // Compress with gzip
        $gzPath = "{$tempPath}.gz";
        exec('gzip -c '.escapeshellarg($tempPath).' > '.escapeshellarg($gzPath));
        unlink($tempPath); // Delete raw SQL, keep .gz only

        $size = round(filesize($gzPath) / 1024 / 1024, 2); // MB

        $this->info("✅ Backup created: {$filename}.gz ({$size} MB)");

        \Log::channel('backup')->info('Database backup created', [
            'file'      => "{$filename}.gz",
            'size_mb'   => $size,
            'timestamp' => now(),
        ]);

        // Cleanup: keep only last BACKUP_RETENTION_COUNT backups
        $this->cleanupOldBackups();

        return 0;
    }

    private function cleanupOldBackups(): void
    {
        $files = glob(storage_path('app/backup/prolabios-*.sql.gz'));

        if (count($files) <= self::BACKUP_RETENTION_COUNT) {
            return;
        }

        // Sort ascending by modified time (oldest first)
        usort($files, fn ($a, $b) => filemtime($a) <=> filemtime($b));

        $toDelete = array_slice($files, 0, count($files) - self::BACKUP_RETENTION_COUNT);

        foreach ($toDelete as $file) {
            unlink($file);
            $this->warn('🗑️ Deleted old backup: '.basename($file));
        }
    }
}