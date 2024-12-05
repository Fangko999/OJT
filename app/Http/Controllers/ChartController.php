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
}
