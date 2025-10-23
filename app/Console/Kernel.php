<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Debug: bikin task dummy untuk cek
        $schedule->call(function () {
            echo ">>> Scheduler jalan: " . now() . "\n";
        })->everyMinute();

        // Jalankan command custom setiap menit
        $schedule->command('activate:pending')
            ->everyMinute()
            ->appendOutputTo(storage_path('logs/schedule.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Laravel otomatis load semua command di app/Console/Commands
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
