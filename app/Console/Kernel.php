<?php

namespace App\Console;

use App\Console\Commands\CalculatePayroll;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        CalculatePayroll::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('send:attendance-reminder')->everyMinute();
        $schedule->command('payroll:calculate')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    protected $routeMiddleware = [
        // ...
        'checklogin' => \App\Http\Middleware\CheckLogin::class,
    ];
   

}
