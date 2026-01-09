<?php

namespace App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class ScheduleServiceProvider extends ServiceProvider
{
    public function boot(Schedule $schedule): void
    {
        // Menjalankan command setiap jam 08:00 pagi
    $schedule->command('reminder:whatsapp')->dailyAt('08:00');
    }
}

