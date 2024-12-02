<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\CarbonPeriod;

class PayrollController extends Controller
{
    public function showPayrollForm()
    {
        $users = User::where('status', 1)
            ->where('role', '!=', '1')
            ->where('salary_level_id', '!=', null)
            ->get();
        return view('fe_payroll/payroll', compact('users'));
    }

    public function calculatePayroll(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $workDays = CarbonPeriod::create($monthStart, $monthEnd)
            ->filter(function ($date) {

                return !$date->isWeekend();
            });

        $totalWorkDays = $workDays->count();

        $user = User::with('salaryLevel')->findOrFail($request->user_id);

        $salaryCoefficient = $user->salaryLevel->salary_coefficient ?? 1;
            $monthlySalary = $user->salaryLevel->monthly_salary ?? 0;
            $nameSalary = $user->salaryLevel->level_name ?? 'Chưa có bậc lương';

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


        return view('fe_payroll/calculate_payroll', compact('user', 'validDays', 'invalidDays', 'nameSalary', 'salaryReceived', 'salaryCoefficient'));
    }

    public function storePayroll(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary_received' => 'required|numeric',
            'valid_days' => 'required|integer',
            'invalid_days' => 'required|integer',
            'salary_coefficient' => 'required|numeric',
            'name_salary' => 'required|string|max:255',
        ]);

        // Kiểm tra xem nhân viên đã có bảng lương trong tháng này chưa
        $existingPayroll = Payroll::where('user_id', $request->user_id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->first();

        if ($existingPayroll) {
            // Cập nhật bảng lương nếu đã có
            $existingPayroll->salary_received = $request->salary_received;
            $existingPayroll->valid_days = $request->valid_days;
            $existingPayroll->invalid_days = $request->invalid_days;
            $existingPayroll->salary_coefficient = $request->salary_coefficient;
            $existingPayroll->name_salary = $request->name_salary;
            $existingPayroll->save();
        } else {
            // Tạo mới bảng lương nếu chưa có
            Payroll::create([
                'user_id' => $request->user_id,
                'salary_received' => $request->salary_received,
                'valid_days' => $request->valid_days,
                'invalid_days' => $request->invalid_days,
                'salary_coefficient' => $request->salary_coefficient,
                'name_salary' => $request->name_salary,
                'month' => now(),
            ]);
        }

        // Lấy thông tin nhân viên để gửi email
        $user = User::find($request->user_id);
        $day = now()->format('d/m/Y');
        // Gửi email thông báo cho nhân viên
        Mail::send('fe_email.salary_notification', [
            'day' => $day,
            'user' => $user->name,
            'salary_received' => $request->salary_received,
            'valid_days' => $request->valid_days,
            'invalid_days' => $request->invalid_days,
            'salary_coefficient' => $request->salary_coefficient,
            'name_salary' => $request->name_salary,
        ], function ($email) use ($user) {
            $email->subject('Thông báo lương tháng ' . now()->format('m/Y'));
            $email->to($user->email, $user->name);
        });

        return redirect()->route('payroll.calculate')->with('success', 'Bảng lương đã được lưu thành công.');
    }

    public function showPayrolls(Request $request)
    {
        // Lấy từ input tìm kiếm
        $search = $request->input('search');

        // Lọc payrolls theo tên nhân viên
        $payrolls = Payroll::with('user')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%'); // Tìm kiếm theo tên nhân viên
                });
            })
            ->paginate(5);

        return view('fe_payroll/user_payroll', compact('payrolls', 'search'));
    }
}
