<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Payroll;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CalculatePayroll extends Command
{
    protected $signature = 'payroll:calculate {--testTime=}';
    protected $description = 'Tính lương cho nhân viên hàng ngày';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Cố định thời gian tính lương là 23:00 hàng ngày
        $payTime = Carbon::createFromTime(23, 0, 0);  // 23:00:00

        // Kiểm tra nếu có tham số 'testTime' thì sử dụng thời gian test, nếu không thì dùng thời gian hiện tại
        $testTime = $this->option('testTime');
        $currentTime = $testTime
            ? Carbon::createFromFormat('H:i:s', $testTime)
            : Carbon::now();

        Log::info("Thời gian tính lương cố định: {$payTime->format('H:i:s')}");
        Log::info("Thời gian hiện tại để kiểm tra: {$currentTime->format('H:i:s')}");

        // Kiểm tra nếu thời gian hiện tại chưa đến 23:00 thì dừng lại
        if (!$currentTime->greaterThanOrEqualTo($payTime)) {
            Log::info("Thời gian hiện tại ({$currentTime->format('H:i:s')}) chưa đạt đến thời gian tính lương ({$payTime->format('H:i:s')}).");
            return;
        }

        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        // Lấy danh sách các ngày làm việc trong tháng (không tính cuối tuần)
        $workDays = CarbonPeriod::create($monthStart, $monthEnd)
            ->filter(function ($date) {
                return !$date->isWeekend();
            });

        Log::info("Tháng này có: {$workDays->count()} ngày làm việc");

        $totalWorkDays = $workDays->count();

        // Lấy danh sách nhân viên đang hoạt động
        $users = User::with('salaryLevel')
            ->where('role', 2)
            ->where('status', 1)
            ->get();

        if ($users->isEmpty()) {
            Log::warning("Không có nhân viên nào để tính lương.");
            return;
        }

        $payrollController = new \App\Http\Controllers\PayrollController();

        // Duyệt qua từng nhân viên và tính lương
        foreach ($users as $user) {
            list($validDays, $invalidDays, $nameSalary, $salaryReceived, $salaryCoefficient) = $payrollController->calculateSalary($user);

            Log::info("Tính lương cho nhân viên: {$user->name}, Lương nhận được: {$salaryReceived}, Ngày hợp lệ: {$validDays}, Ngày không hợp lệ: {$invalidDays}, Hệ số lương: {$salaryCoefficient}");

            // Tạo request giả để gọi hàm storePayroll
            $request = new \Illuminate\Http\Request([
                'user_id' => $user->id,
                'salary_received' => $salaryReceived,
                'valid_days' => $validDays,
                'invalid_days' => $invalidDays,
                'salary_coefficient' => $salaryCoefficient,
                'name_salary' => $nameSalary,
            ]);

            $payrollController->storePayroll($request);
        }

        $this->info('Lương của tất cả nhân viên đã được tính toán.');
    }
}