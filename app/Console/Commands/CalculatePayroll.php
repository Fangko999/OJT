<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Payroll;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $payTime = Carbon::createFromTime(22, 30, 0);  // 23:00:00

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

        // Duyệt qua từng nhân viên và tính lương
        foreach ($users as $user) {
            $salaryCoefficient = $user->salaryLevel->salary_coefficient ?? 1;
            $monthlySalary = $user->salaryLevel->monthly_salary ?? 0;

            $attendances = DB::table('user_attendance')
                ->where('user_id', $user->id)
                ->where('type', 'out')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->get();

            $validDays = $attendances->where('status', 1)->count();
            $invalidDays = $attendances->whereIn('status', [0, 2, 3])->count();

            // Tính lương cho ngày hợp lệ
            $validSalary = (($monthlySalary * $salaryCoefficient) / $totalWorkDays) * $validDays;

            // Trừ 50% lương cho ngày không hợp lệ
            $invalidSalaryPenalty = (($monthlySalary * $salaryCoefficient) / $totalWorkDays) * $invalidDays * 0.5;

            // Tính lương cuối cùng
            $salaryReceived = $validSalary + $invalidSalaryPenalty;

            Log::info("Tính lương cho nhân viên: {$user->name}, Lương nhận được: {$salaryReceived}, Ngày hợp lệ: {$validDays}, Ngày không hợp lệ: {$invalidDays}, Hệ số lương: {$salaryCoefficient}");

            // Kiểm tra xem nhân viên đã có bảng lương trong tháng này chưa
            $existingPayroll = Payroll::where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->first();

            if ($existingPayroll) {
                // Cập nhật bảng lương nếu đã có
                $existingPayroll->salary_received = $salaryReceived;
                $existingPayroll->valid_days = $validDays;
                $existingPayroll->invalid_days = $invalidDays;
                $existingPayroll->salary_coefficient = $salaryCoefficient;
                $existingPayroll->save();
            } else {
                // Tạo mới bảng lương nếu chưa có
                Payroll::create([
                    'user_id' => $user->id,
                    'salary_received' => $salaryReceived,
                    'valid_days' => $validDays,
                    'invalid_days' => $invalidDays,
                    'salary_coefficient' => $salaryCoefficient,
                    'month' => now(),
                ]);
            }
        }

        $this->info('Lương của tất cả nhân viên đã được tính toán.');
    }
}