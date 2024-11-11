<?php

namespace App\Http\Controllers;

use App\Models\salary_level;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index(Request $request)
{
    // Lấy giá trị tìm kiếm từ request
    $searchSalary = $request->input('search_salary');

    // Tạo truy vấn cơ bản
    $query = salary_level::query();

    // Nếu có giá trị tìm kiếm, tìm trong tên cấp bậc
    if ($searchSalary) {
        $query->where('name', 'LIKE', '%' . $searchSalary . '%');
    }

    // Lấy kết quả
    $salarylevels = $query->get();

    return view('fe_salary.salary', compact('salarylevels'));
}

    public function create()
    {
        return view('fe_salary.salary_create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:50|unique:salary_level,name',
        'monthly_salary' => 'required|string|unique:salary_level,monthly_salary',
        'daily_salary' => 'required|string|unique:salary_level,daily_salary',
    ], [
        'name.required' => 'Tên bậc lương không được để trống!',
        'name.unique' => 'Bậc lương này đã tồn tại!',
        'monthly_salary.required' => 'Lương tháng không được để trống!',
        'daily_salary.required' => 'Lương ngày không được để trống!',
    ]);

    $monthlySalary = $this->parseSalary($request->monthly_salary);
    $dailySalary = $this->parseSalary($request->daily_salary);

    // Kiểm tra xem lương tháng có lớn hơn lương ngày không
    if ($monthlySalary <= $dailySalary) {
        return redirect()->back()->withErrors(['monthly_salary' => 'Lương tháng phải lớn hơn lương ngày.']);
    }

    // Kiểm tra mức lương tháng và ngày đã tồn tại hay chưa
    $existingSalaryLevel = salary_level::where('monthly_salary', $monthlySalary)
        ->where('daily_salary', $dailySalary)
        ->first();

    if ($existingSalaryLevel) {
        return redirect()->back()->withErrors([
            'salary_level' => 'Mức này đã tồn tại. Vui lòng kiểm tra lại.'
        ]);
    }

    // Thêm mức lương mới
    $userId = auth()->user()->id;

    salary_level::create([
        'name' => $request->name,
        'monthly_salary' => $monthlySalary,
        'daily_salary' => $dailySalary,
        'status' => 1,
        'created_at' => now(),
        'created_by' => $userId,
        'updated_at' => now(),
        'updated_by' => $userId,
    ]);

    return redirect()->route('salary')->with('success', 'Thêm thành công');
}



        /**
         * Parse a salary string into an integer
         *
         * @param string $salary salary string, e.g. "1,000,000 VND"
         * @return int the parsed salary integer
         */
    private function parseSalary($salary)
    {
        return intval(str_replace(['₫', '.', ','], '', $salary));
    }

    public function show($id)
    {
        // Tìm mức lương theo ID
        $salaryLevel = Salary_level::with(['creator', 'updater'])->findOrFail($id);

        return view('fe_salary.salary_detail', compact('salaryLevel'));
    }
    public function edit($id)
    {
        // Tìm mức lương theo ID
        $salaryLevel = Salary_level::findOrFail($id);

        return view('fe_salary.salary_detail', compact('salaryLevel'));
    }

    public function update(Request $request, $id)
{
    // Lấy mức lương từ cơ sở dữ liệu
    $salaryLevel = salary_level::findOrFail($id);

    // Tiến hành validate
    $request->validate([
        'name' => 'required|string|max:50|unique:salary_level,name,' . $salaryLevel->id,
        'monthly_salary' => 'required|string|unique:salary_level,monthly_salary,' . $salaryLevel->id,
        'daily_salary' => 'required|string|unique:salary_level,daily_salary,' . $salaryLevel->id,
    ], [
        'name.required' => 'Tên bậc lương không được để trống!',
        'name.unique' => 'Bậc lương này đã tồn tại.',
        'monthly_salary.required' => 'Lương tháng không được để trống!',
        'daily_salary.required' => 'Lương ngày không được để trống!',
    ]);

    $monthlySalary = $this->parseSalary($request->monthly_salary);
    $dailySalary = $this->parseSalary($request->daily_salary);

    // Kiểm tra xem lương tháng có lớn hơn lương ngày không
    if ($monthlySalary <= $dailySalary) {
        return redirect()->back()->withErrors(['monthly_salary' => 'Lương tháng phải lớn hơn lương ngày.']);
    }

    // Kiểm tra mức lương tháng và ngày đã tồn tại hay chưa (trừ bản ghi hiện tại)
    $existingSalaryLevel = salary_level::where('monthly_salary', $monthlySalary)
        ->where('daily_salary', $dailySalary)
        ->where('id', '!=', $salaryLevel->id)
        ->first();

    if ($existingSalaryLevel) {
        return redirect()->back()->withErrors([
            'salary_level' => 'Mức lương này đã tồn tại. Vui lòng kiểm tra lại.'
        ]);
    }

    // Cập nhật thông tin mức lương
    $salaryLevel->update([
        'name' => $request->name,
        'monthly_salary' => $monthlySalary,
        'daily_salary' => $dailySalary,
        'status' => $request->status,
        'updated_at' => now(),
        'updated_by' => auth()->user()->id,
    ]);

    return redirect()->route('salary')->with('success', 'Cập nhật thành công');
}

// Phương thức destroy để xóa bậc lương
public function destroy($id)
{
    // Tìm mức lương theo ID
    $salaryLevel = salary_level::findOrFail($id);

    // Xóa mức lương
    $salaryLevel->delete();

    // Trả về trang danh sách với thông báo thành công
    return redirect()->route('salary')->with('success', 'Xóa bậc lương thành công');
}

}
