<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DatabaseBackup extends Command
{
    protected $signature = 'backup:database';

    protected $aliases = ['db:backup'];

    protected $description = 'Create a compressed database backup and clean old backups';

    /** Number of most-recent backups to keep; older files are deleted */
    private const BACKUP_RETENTION_COUNT = 7;

    public function handle(): int
    {
        $db = config('database.connections.mysql');
        $timestamp = now()->format('Y-m-d-His');
        $filename = "prolabios-{$timestamp}.sql";
        $tempPath = storage_path("app/backup/{$filename}");

        // Ensure backup directory exists
        if (! is_dir(storage_path('app/backup'))) {
            mkdir(storage_path('app/backup'), 0755, true);
        }

        // Create temporary MySQL defaults-extra-file to avoid exposing credentials in CLI process args
        $cnfFile = tempnam(sys_get_temp_dir(), 'mycnf_');
        $cnfContent = "[mysqldump]\n"
            .'host = "'.addcslashes($db['host'] ?? '127.0.0.1', '"\\')."\"\n"
            .'user = "'.addcslashes($db['username'] ?? '', '"\\')."\"\n"
            .'password = "'.addcslashes($db['password'] ?? '', '"\\')."\"\n";
        file_put_contents($cnfFile, $cnfContent);

        $dumpBinary = $this->getMysqldumpBinary();
        $nullDevice = PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null';

        // Build mysqldump command using defaults file
        $command = sprintf(
            '%s --defaults-extra-file=%s --single-transaction --quick --lock-tables=false %s > %s 2>%s',
            escapeshellarg($dumpBinary),
            escapeshellarg($cnfFile),
            escapeshellarg($db['database']),
            escapeshellarg($tempPath),
            $nullDevice
        );

        exec($command, $output, $exitCode);

        // Immediately purge the temporary config file containing DB credentials
        if (file_exists($cnfFile)) {
            unlink($cnfFile);
        }

        if ($exitCode !== 0 || ! file_exists($tempPath) || filesize($tempPath) === 0) {
            $this->error('Database backup failed!');
            Log::channel('backup')->error('Database backup failed', [
                'exit_code' => $exitCode,
                'timestamp' => now(),
            ]);

            return 1;
        }

        // Compress using PHP native zlib (gzopen) — zero dependency on external gzip CLI binary
        $gzPath = "{$tempPath}.gz";
        $compressed = $this->compressWithGzip($tempPath, $gzPath);
        if ($compressed && file_exists($tempPath)) {
            unlink($tempPath); // Delete raw SQL, keep .gz only
        }

        $size = round(filesize($gzPath) / 1024 / 1024, 2); // MB

        $this->info("✅ Backup created: {$filename}.gz ({$size} MB)");

        Log::channel('backup')->info('Database backup created', [
            'file' => "{$filename}.gz",
            'size_mb' => $size,
            'timestamp' => now(),
        ]);

        // Cleanup: keep only last BACKUP_RETENTION_COUNT backups
        $this->cleanupOldBackups();

        return 0;
    }

    protected function getMysqldumpBinary(): string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $laragonMatches = glob('C:/laragon/bin/mysql/*/bin/mysqldump.exe') ?: [];
            if (! empty($laragonMatches) && file_exists($laragonMatches[0])) {
                return $laragonMatches[0];
            }
            $xampp = 'C:/xampp/mysql/bin/mysqldump.exe';
            if (file_exists($xampp)) {
                return $xampp;
            }
        }

        return 'mysqldump';
    }

    protected function compressWithGzip(string $source, string $destination): bool
    {
        if (function_exists('gzopen')) {
            $fpIn = fopen($source, 'rb');
            $fpOut = gzopen($destination, 'wb9');
            if ($fpIn && $fpOut) {
                while (! feof($fpIn)) {
                    $chunk = fread($fpIn, 1024 * 512);
                    if ($chunk !== false) {
                        gzwrite($fpOut, $chunk);
                    }
                }
                fclose($fpIn);
                gzclose($fpOut);

                return true;
            }
            if ($fpIn) {
                fclose($fpIn);
            }
            if ($fpOut) {
                gzclose($fpOut);
            }
        }

        // Fallback to exec gzip if available
        exec('gzip -c '.escapeshellarg($source).' > '.escapeshellarg($destination));

        return file_exists($destination) && filesize($destination) > 0;
    }

    private function cleanupOldBackups(): void
    {
        $files = glob(storage_path('app/backup/prolabios-*.sql.gz')) ?: [];

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
