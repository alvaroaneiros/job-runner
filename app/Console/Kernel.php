<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Schedule the queue worker to process jobs every minute
        $schedule->command('queue:work --stop-when-empty')
                 ->everyMinute()
                 ->withoutOverlapping() // Prevent overlapping workers
                 ->appendOutputTo(storage_path('logs/queue-worker.log')); // Log output to a file

        // Example of scheduling other commands
        // $schedule->command('backup:run')->dailyAt('02:00');
        // $schedule->command('emails:send')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // Automatically load custom Artisan commands
        $this->load(__DIR__ . '/Commands');

        // Load the routes/console.php file for defining console routes
        require base_path('routes/console.php');
    }
}
