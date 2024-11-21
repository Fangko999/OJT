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
        
        // Check-in reminder at 12:40
        if ($currentTime === '12:47') {
            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new EmailReminder($user, 'check-in'));
                $this->info("Check-in email sent to {$user->email} at 12:40");
            }
        }

        // Check-out reminder at 12:41
        if ($currentTime === '12:47') {
            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new EmailCheckoutReminder($user, 'check-out'));
                $this->info("Check-out email sent to {$user->email} at 12:41");
            }
        }

        return Command::SUCCESS;
    }
}
