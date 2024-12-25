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
        $departments = DB::table('departments')->get();
        $users = User::where('status', 1)
            ->where('role', '!=', '1')
            ->where('salary_level_id', '!=', null)
            ->get();
        return view('fe_payroll/payroll', compact('users', 'departments'));
    }

    public function getUsersByDepartment(Request $request)
    {
        $departmentId = $request->department_id;
        $users = User::where('status', 1)
            ->where('role', '!=', '1')
            ->where('salary_level_id', '!=', null)
            ->when($departmentId, function ($query, $departmentId) {
                return $query->where('department_id', $departmentId);
            })
            ->get();
        return response()->json($users);
    }

    public function calculatePayroll(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $user = User::with('salaryLevel')->findOrFail($request->user_id);

        list($validDays, $invalidDays, $nameSalary, $salaryReceived, $salaryCoefficient) = $this->calculateSalary($user);

        return view('fe_payroll/calculate_payroll', compact('user', 'validDays', 'invalidDays', 'nameSalary', 'salaryReceived', 'salaryCoefficient'));
    }

    public function calculateSalary($user)
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $workDays = CarbonPeriod::create($monthStart, $monthEnd)
            ->filter(function ($date) {
                return !$date->isWeekend();
            });

        $totalWorkDays = $workDays->count();

        $salaryCoefficient = $user->salaryLevel->salary_coefficient ?? 0;
        $monthlySalary = $user->salaryLevel->monthly_salary ?? 0;
        $dailySalary = $user->salaryLevel->daily_salary ?? 0; // Assuming daily_salary is available
        $nameSalary = $user->salaryLevel->level_name ?? 'Chưa có bậc lương';

        $attendances = DB::table('user_attendance')
            ->where('user_id', $user->id)
            ->whereMonth('time', now()->month)
            ->whereYear('time', now()->year)
            ->get()
            ->groupBy(function ($attendance) {
                return \Carbon\Carbon::parse($attendance->time)->toDateString();
            });

        $validDays = $attendances->filter(function ($attendanceGroup) use ($user) {
            $inStatus = $attendanceGroup->where('type', 'in')->first()->status ?? null;
            $outStatus = $attendanceGroup->where('type', 'out')->first()->status ?? null;

            return $inStatus == 1 && $outStatus == 1;
        })->count();

        $invalidDays = $attendances->count() - $validDays;

        if ($user->role == 2) {
            $validSalary = (($monthlySalary * $salaryCoefficient) / $totalWorkDays) * $validDays;
            $invalidSalaryPenalty = (($monthlySalary * $salaryCoefficient) / $totalWorkDays) * $invalidDays * 0.5;
            $salaryReceived = $validSalary + $invalidSalaryPenalty;
        } elseif ($user->role == 3) {
            $validSalary = $dailySalary * $validDays;
            $invalidSalaryPenalty = ($dailySalary * $invalidDays) * 0.5;
            $salaryReceived = $validSalary + $invalidSalaryPenalty;
        } else {
            $salaryReceived = 0; // Default case if role is not 2 or 3
        }

        return [$validDays, $invalidDays, $nameSalary, $salaryReceived, $salaryCoefficient];
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

        // Gửi email thông báo cho nhân viên
        $user = User::find($request->user_id);
        $this->sendSalaryNotification($user, $request->salary_received, $request->valid_days, $request->invalid_days, $request->salary_coefficient, $request->name_salary);

        return redirect()->route('payroll.calculate')->with('success', 'Bảng lương đã được lưu thành công.');
    }

    private function sendSalaryNotification($user, $salaryReceived, $validDays, $invalidDays, $salaryCoefficient, $nameSalary)
    {
        $day = now()->format('d/m/Y');
        Mail::send('fe_email.salary_notification', [
            'day' => $day,
            'user' => $user->name,
            'salary_received' => $salaryReceived,
            'valid_days' => $validDays,
            'invalid_days' => $invalidDays,
            'salary_coefficient' => $salaryCoefficient,
            'name_salary' => $nameSalary,
        ], function ($email) use ($user) {
            $email->subject('Thông báo lương tháng ' . now()->format('m/Y'));
            $email->to($user->email, $user->name);
        });
    }

    public function showPayrolls(Request $request)
    {
        // Lấy từ input tìm kiếm và tháng
        $search = $request->input('search');
        $month = $request->input('month');

        // Lọc payrolls theo tên nhân viên và tháng, sắp xếp theo ngày cập nhật
        $payrolls = Payroll::with('user')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%'); // Tìm kiếm theo tên nhân viên
                });
            })
            ->when($month, function ($query, $month) {
                $parsedMonth = \Carbon\Carbon::parse($month);
                return $query->whereMonth('updated_at', $parsedMonth->month)
                             ->whereYear('updated_at', $parsedMonth->year);
            })
            ->orderBy('updated_at', 'desc') // Sắp xếp theo ngày cập nhật
            ->paginate(5);

        return view('fe_payroll/user_payroll', compact('payrolls', 'search', 'month'));
    }
}
