<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ChartController extends Controller
{

    public function chartView()
    {
        return view('fe_charts/charts');
    }

    public function employeeRatioView()
    {
        return view('fe_charts.employee_ratio');
    }

    public function getUserCountByDepartment()
    {
        $data = DB::table('users')
    ->join('departments', 'users.department_id', '=', 'departments.id')
    ->select('departments.name as department_name', DB::raw('count(users.id) as employee_count'))
    ->groupBy('departments.name')
    ->orderBy('departments.name', 'asc')  // Sắp xếp theo tên phòng ban (tăng dần)
    ->get();

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

        $users = DB::table('users')->select('id', 'name')->get();

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
}
