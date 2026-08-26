<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * @deprecated One-time JSON→DB importer. Data now lives in migrations/seeders.
 * Command kept only so old scripts fail safely instead of truncating tables.
 */
class MigrateJsonToDb extends Command
{
    protected $signature = 'database:migrate-json {--force : Ignored; command is disabled}';

    protected $description = '[REMOVED] JSON→DB one-time import is disabled. Use migrations/seeders.';

    public function handle(): int
    {
        $this->error('database:migrate-json has been removed.');
        $this->line('Use database migrations and seeders instead. This command no longer truncates or imports data.');

        return self::FAILURE;
    }
}
