<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Payroll;

class ChartController extends Controller
{

    public function chartView()
    {
        $departments = Department::all();
        return view('fe_charts/charts', compact('departments'));
    }

    public function showCharts()
    {
        $departments = Department::all();
        return view('fe_charts.charts', compact('departments'));
    }

    public function employeeRatioView()
    {
        $departments = Department::all();
        return view('fe_charts.employee_ratio', compact('departments'));
    }

    public function getUserCountByDepartment(Request $request)
    {
        $query = DB::table('departments')
            ->leftJoin('users', 'users.department_id', '=', 'departments.id')
            ->select('departments.name as department_name', DB::raw('count(users.id) as employee_count'))
            ->groupBy('departments.name')
            ->orderBy('departments.name', 'asc');

        if ($request->has('departments') && $request->departments != '') {
            $departmentIds = explode(',', $request->departments);
            $query->whereIn('departments.id', $departmentIds);
        }

        $data = $query->get();

        return response()->json([
            'labels' => $data->pluck('department_name'),
            'counts' => $data->pluck('employee_count'),
        ]);
    }

    public function getGenderRatioByDepartment($departmentId)
    {
        $data = DB::table('users')
            ->select(DB::raw('gender, count(id) as count'))
            ->where('department_id', $departmentId)
            ->groupBy('gender')
            ->get();

        $genderRatio = [
            'male' => $data->where('gender', 1)->first()->count ?? 0,
            'female' => $data->where('gender', 0)->first()->count ?? 0,
        ];

        return response()->json($genderRatio);
    }

    public function genderRatioView()
    {
        $departments = Department::all();
        return view('fe_charts.gender_ratio', compact('departments'));
    }


    public function attendanceRatioView()
    {
        $departments = Department::all();
        return view('fe_charts.attendence_ratio', compact('departments'));
    }

    public function getAttendanceRatio(Request $request)
    {
        $startDate = $request->input('start_date', now()->subWeek()->startOfDay());
        $endDate = $request->input('end_date', now()->endOfDay());
        $departmentId = $request->input('department');
        $userName = $request->input('user_name');

        $query = DB::table('users')->select('id', 'name');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($userName) {
            $query->where('name', 'like', '%' . $userName . '%');
        }

        $users = $query->get();

        $attendanceStats = [];

        foreach ($users as $user) {
            $attendances = DB::table('user_attendance')
                ->where('user_id', $user->id)
                ->whereBetween('time', [$startDate, $endDate])
                ->get()
                ->groupBy(function ($attendance) {
                    return \Carbon\Carbon::parse($attendance->time)->toDateString();
                });

            if ($attendances->isNotEmpty()) {
                $validDaysForUser = $attendances->filter(function ($attendanceGroup) {
                    $inStatus = $attendanceGroup->where('type', 'in')->first()->status ?? null;
                    $outStatus = $attendanceGroup->where('type', 'out')->first()->status ?? null;

                    return $inStatus == 1 && $outStatus == 1;
                })->count();

                $invalidDaysForUser = $attendances->count() - $validDaysForUser;

                $attendanceStats[] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'valid_days' => $validDaysForUser,
                    'invalid_days' => $invalidDaysForUser,
                ];
            }
        }

        return response()->json($attendanceStats);
    }

    public function getAgeRatioByDepartment($departmentId)
    {
        $data = DB::table('users')
            ->select(DB::raw('
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 30 THEN "18-30"
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 31 AND 39 THEN "31-39"
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 40 AND 49 THEN "40-49"
                    ELSE "50+"
                END as age_range,
                count(id) as count
            '))
            ->where('department_id', $departmentId)
            ->groupBy('age_range')
            ->get();

        $ageDistribution = [
            'Từ 18 đến 30 tuổi' => $data->where('age_range', '18-30')->first()->count ?? 0,
            'Từ 31 đến 39 tuổi' => $data->where('age_range', '31-39')->first()->count ?? 0,
            'Từ 40 đến 49 tuổi' => $data->where('age_range', '40-49')->first()->count ?? 0,
            'Trên 50 tuổi' => $data->where('age_range', '50+')->first()->count ?? 0,
        ];

        return response()->json($ageDistribution);
    }

    public function ageRatioView()
    {
        $departments = Department::all();
        return view('fe_charts.age_ratio', compact('departments'));
    }

    public function salaryStatisticsView()
    {
        $departments = Department::all();
        return view('fe_charts.salary_statistics', compact('departments'));
    }

    public function getSalaryStatisticsByMonth(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $departmentId = $request->input('department_id');

        $query = DB::table('payrolls')
            ->join('users', 'payrolls.user_id', '=', 'users.id')
            ->select('users.name as user_name', DB::raw('SUM(salary_received) as salary_received'))
            ->where(DB::raw('DATE_FORMAT(payrolls.created_at, "%Y-%m")'), $month);

        if ($departmentId) {
            $query->where('users.department_id', $departmentId);
        }

        $data = $query->groupBy('users.name')->get();

        return response()->json([
            'labels' => $data->pluck('user_name'),
            'salaries' => $data->pluck('salary_received'),
        ]);
    }

    public function getSeniorityRatioByDepartment($departmentId = null)
    {
        $query = DB::table('users')
            ->select(DB::raw('
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, created_at, CURDATE()) < 1 THEN "Dưới 1 năm"
                    WHEN TIMESTAMPDIFF(YEAR, created_at, CURDATE()) BETWEEN 1 AND 3 THEN "1-3 năm"
                    WHEN TIMESTAMPDIFF(YEAR, created_at, CURDATE()) BETWEEN 3 AND 5 THEN "3-5 năm"
                    ELSE "Trên 5 năm"
                END as seniority_range,
                count(id) as count
            '));

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $data = $query->groupBy('seniority_range')->get();

        $seniorityDistribution = [
            'Dưới 1 năm' => $data->where('seniority_range', 'Dưới 1 năm')->first()->count ?? 0,
            '1-3 năm' => $data->where('seniority_range', '1-3 năm')->first()->count ?? 0,
            '3-5 năm' => $data->where('seniority_range', '3-5 năm')->first()->count ?? 0,
            'Trên 5 năm' => $data->where('seniority_range', 'Trên 5 năm')->first()->count ?? 0,
        ];

        return response()->json($seniorityDistribution);
    }

    public function seniorityRatioView()
    {
        $departments = Department::all();
        return view('fe_charts.seniority_ratio', compact('departments'));
    }

    public function getSeniorityRatio(Request $request)
    {
        $departmentId = $request->input('department_id');

        $query = DB::table('users')
            ->select(DB::raw('
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, created_at, CURDATE()) < 1 THEN "Dưới 1 năm"
                    WHEN TIMESTAMPDIFF(YEAR, created_at, CURDATE()) BETWEEN 1 AND 3 THEN "1-3 năm"
                    WHEN TIMESTAMPDIFF(YEAR, created_at, CURDATE()) BETWEEN 3 AND 5 THEN "3-5 năm"
                    ELSE "Trên 5 năm"
                END as seniority_range,
                count(id) as count
            '));

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $data = $query->groupBy('seniority_range')->get();

        $seniorityDistribution = [
            'Dưới 1 năm' => $data->where('seniority_range', 'Dưới 1 năm')->first()->count ?? 0,
            '1-3 năm' => $data->where('seniority_range', '1-3 năm')->first()->count ?? 0,
            '3-5 năm' => $data->where('seniority_range', '3-5 năm')->first()->count ?? 0,
            'Trên 5 năm' => $data->where('seniority_range', 'Trên 5 năm')->first()->count ?? 0,
        ];

        return response()->json($seniorityDistribution);
    }
}
