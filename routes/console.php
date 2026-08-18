<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Schedule backup otomatis setiap jam 02:00 pagi
Schedule::command('backup:database')->dailyAt('02:00');

// (Opsional) Restart queue jika pakai worker
// Schedule::command('queue:restart')->dailyAt('02:05');