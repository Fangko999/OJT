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

    public function getGenderCountByDepartment()
    {
        $data = DB::table('users')
            ->join('departments', 'users.department_id', '=', 'departments.id')
            ->select('departments.name as department_name', 'users.gender', DB::raw('count(users.id) as gender_count'))
            ->groupBy('departments.name', 'users.gender')
            ->orderBy('departments.name', 'asc')
            ->get();

        $result = [];
        foreach ($data as $row) {
            $result[$row->department_name][$row->gender] = $row->gender_count;
        }

        return response()->json($result);
    }
}
