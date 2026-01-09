<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\KirimReminderCommand;
use App\Console\Commands\PeriksaDokumenKadaluarsa;

class Kernel extends ConsoleKernel
{
    /**
     * Daftarkan semua command schedule di sini.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Menjadwalkan command reminder:whatsapp setiap hari pukul 19:10
        $schedule->command('reminder:whatsapp')->dailyAt('19:10');
    }

    /**
     * Daftarkan command artisan kustom di sini.
     */
    protected function commands(): void
    {
        // Load semua command dari folder Commands
        $this->load(__DIR__ . '/Commands');

        // Daftarkan semua command custom di sini
        $this->commands([
            KirimReminderCommand::class,
            PeriksaDokumenKadaluarsa::class,
        ]);

        // Jika ada file command closure
        require base_path('routes/console.php');
    }
}
