<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Mail\CheckInMail;
use App\Mail\CheckOutMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Gửi email Check In lúc 8h sáng
        $schedule->call(function () {
            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new CheckInMail());
            }
        })->dailyAt('08:00');
    
        // Gửi email Check Out lúc 17h chiều
        $schedule->call(function () {
            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new CheckOutMail());
            }
        })->dailyAt('12:20');
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
