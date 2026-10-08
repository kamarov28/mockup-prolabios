<?php

use Illuminate\Support\Facades\Schedule;

// Schedule backup otomatis setiap jam 02:00 pagi
Schedule::command('backup:database')->dailyAt('02:00');

// Proses antrean email RFQ & kontak di shared hosting (aman tanpa supervisor daemon)
Schedule::command('queue:work --stop-when-empty --tries=3 --max-time=50')
    ->everyMinute()
    ->withoutOverlapping();
