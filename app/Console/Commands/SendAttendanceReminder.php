<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Mail\EmailReminder;
use App\Mail\EmailCheckoutReminder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAttendanceReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:attendance-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi email nhắc nhở đến tất cả người dùng vào thời gian đã định';

    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle()
    {
        $currentTime = Carbon::now()->format('H:i');

        // Send check-in reminders
        $checkinUsers = User::where('remind_checkin', $currentTime)->get();
        foreach ($checkinUsers as $user) {
            Mail::to($user->email)->send(new EmailCheckinReminder($user, 'checkin'));
            $this->info("Check-in email sent to {$user->email} at {$user->remind_checkin}");
        }

        // Send check-out reminders
        $checkoutUsers = User::where('remind_checkout', $currentTime)->get();
        foreach ($checkoutUsers as $user) {
            Mail::to($user->email)->send(new EmailCheckoutReminder($user, 'checkout'));
            $this->info("Check-out email sent to {$user->email} at {$user->remind_checkout}");
        }

        return Command::SUCCESS;
    }
}
