<?php

namespace App\Http\Controllers;

use App\Models\SalaryLevel;
use Illuminate\Http\Request;

class SalaryLevelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $salaryLevels = SalaryLevel::when($search, function ($query, $search) {
            return $query->where('level_name', 'like', '%' . $search . '%');
        })->where('is_active', 1)->paginate(4);

        return view('fe_salary/salary', compact('salaryLevels'));
    }


    public function create()
    {
        return view('fe_salary/salary_create');
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'salary_coefficient' => 'required|numeric',
            'monthly_salary' => 'required|numeric|gte:' . ($request->daily_salary * 20),
            'daily_salary' => 'required|numeric',
        ], [
            'monthly_salary.gte' => 'Lương tháng phải lớn hơn ít nhất 20 lần lương ngày.',
        ]);

        SalaryLevel::create([
            'level_name' => $request->name,
            'salary_coefficient' => $request->salary_coefficient,
            'monthly_salary' => $request->monthly_salary,
            'daily_salary' => $request->daily_salary,
        ]);

        return redirect()->route('salaryLevels')->with('success', 'Bậc lương đã được thêm.');
    }

    public function edit($id)
    {

        $salaryLevel = SalaryLevel::findOrFail($id);

        return view('fe_salary/salary_update', compact('salaryLevel')); 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'salary_coefficient' => 'required|numeric',
            'monthly_salary' => 'required|numeric|gte:' . ($request->daily_salary * 20),
            'daily_salary' => 'required|numeric',
        ], [
            'monthly_salary.gte' => 'Lương tháng phải lớn hơn ít nhất 20 lần lương ngày.',
        ]);

        $salaryLevel = SalaryLevel::findOrFail($id);
        $salaryLevel->level_name = $request->name;
        $salaryLevel->salary_coefficient = $request->salary_coefficient;
        $salaryLevel->monthly_salary = $request->monthly_salary;
        $salaryLevel->daily_salary = $request->daily_salary;

        $salaryLevel->save();

        return redirect()->route('salaryLevels')->with('success', 'Bậc Lương đã được cập nhật.');
    }

    public function softDeleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->route('salaryLevels')->withErrors(['error' => 'Vui lòng chọn ít nhất một bậc lương để xóa.']);
        }

        SalaryLevel::whereIn('id', $ids)->update(['is_active' => 0]);

        return redirect()->route('salaryLevels')->with('success', 'Các bậc lương đã được xóa thành công.');
    }
}
