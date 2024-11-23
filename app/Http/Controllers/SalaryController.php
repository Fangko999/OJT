<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        // Lấy giá trị tìm kiếm từ request
        $searchSalary = $request->input('search_salary');
        $departments = Department::where('parent_id', 0)->get();

        // Tạo truy vấn cơ bản
        $query = Salary::query();

        // Nếu có giá trị tìm kiếm, tìm trong trường 'created_by' hoặc bất kỳ trường nào bạn muốn
        if ($searchSalary) {
            $query->where('salaryCoefficient', 'LIKE', '%' . $searchSalary . '%');
        }

        // Lấy kết quả
        $salaries  = $query->get();

        return view('fe_salary.salary', compact('salaries', 'departments', 'searchSalary'));
    }


    public function create()
    {
        $departments = Department::where('parent_id', 0)->get(); // Lấy những phòng ban có parent_id là 0
        return view('fe_salary.salary_create', compact('departments'));
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'department_id' => 'required|exists:departments,id',
            'salaryCoefficient' => 'required|numeric|between:0,99.99',
            'monthlySalary' => 'required|numeric|between:0,9999999999.99',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'Tên cấp báo báo bắt buộc.',

            'department_id.required' => 'Phòng ban là bắt buộc.',
            'department_id.exists' => 'Phòng ban không tồn tại.',
            'salaryCoefficient.required' => 'Hệ số lương là bắt buộc.',
            'salaryCoefficient.numeric' => 'Hệ số lương phải là số.',
            'salaryCoefficient.between' => 'Hệ số lương phải từ 0 đến 99.99.',
            'monthlySalary.required' => 'Lương tháng là bắt buộc.',
            'monthlySalary.numeric' => 'Lương tháng phải là số.',
            'monthlySalary.between' => 'Lương tháng phải nằm trong khoảng 0 đến 9,999,999,999.99.',
        ]);

        // Chuyển đổi lương tháng từ chuỗi định dạng tiền tệ sang số
        $monthlySalary = $this->parseSalary($request->monthlySalary);
        if ($monthlySalary < 1000000) {
            return redirect()->back()->withErrors(['monthlySalary' => 'Lương tháng phải ít nhất là 1,000,000 VND.']);
        }

        // Tạo mới Salary
        Salary::create([
            'name' => $validatedData['name'],
            'department_id' => $validatedData['department_id'], // department_id giờ đã có thể gán giá trị            'salaryCoefficient' => $validatedData['salaryCoefficient'],
            'salaryCoefficient' => $validatedData['salaryCoefficient'],

            'monthlySalary' => $monthlySalary,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        // Chuyển hướng với thông báo thành công
        return redirect()->route('salary')->with('success', 'Tạo lương thành công.');
    }

    /**
     * Parse a salary string into an integer
     *
     * @param string $salary salary string, e.g. "1,000,000 ₫"
     * @return float the parsed salary value
     */
    


    public function show($id)
    {
        // Tìm mức lương theo ID
        $salaryLevel = Salary::with('department')->findOrFail($id);

        // Assuming `created_by` and `updated_by` are user IDs
        $creator = User::find($salaryLevel->created_by);
        $updater = User::find($salaryLevel->updated_by);   
             $departments = Department::where('parent_id', 0)->get(); // Lấy những phòng ban có parent_id là 0

        return view('fe_salary.salary_detail', compact('salaryLevel', 'departments','creator', 'updater'));
    }
    public function edit($id)
{
    // Tìm mức lương theo ID
    $salary = Salary::findOrFail($id);
    $departments = Department::where('parent_id', 0)->get(); // Lấy những phòng ban có parent_id là 0

    // Trả về view để chỉnh sửa mức lương
    return view('fe_salary.salary_edit', compact('salary', 'departments'));
}

// Add the update method
public function update(Request $request, $id)
{
    // Xác thực dữ liệu đầu vào
    $validatedData = $request->validate([
        'name' => 'required|string|max:50',
        'salaryCoefficient' => 'required|numeric|between:0,99.99',
        'monthlySalary' => 'required|numeric|between:0,9999999999.99',
        'status' => 'required|boolean',
    ], [
        'name.required' => 'Tên cấp báo bắt buộc.',
        'salaryCoefficient.required' => 'Hệ số lương là bắt buộc.',
        'salaryCoefficient.numeric' => 'Hệ số lương phải là số.',
        'salaryCoefficient.between' => 'Hệ số lương phải từ 0 đến 99.99.',
        'monthlySalary.required' => 'Lương tháng là bắt buộc.',
        'monthlySalary.numeric' => 'Lương tháng phải là số.',
        'monthlySalary.between' => 'Lương tháng phải nằm trong khoảng 0 đến 9,999,999,999.99.',
    ]);
    $status = $request->status == 1 ? true : false;

    // Chuyển đổi lương tháng từ chuỗi định dạng tiền tệ sang số
    $monthlySalary = $this->parseSalary($request->monthlySalary);
    
    // Kiểm tra nếu lương tháng nhỏ hơn 1,000,000 VND
    if ($monthlySalary < 1000000) {
        return redirect()->back()->withErrors(['monthlySalary' => 'Lương tháng phải ít nhất là 1,000,000 VND.']);
    }

    // Tìm mức lương cần cập nhật
    $salary = Salary::findOrFail($id);

    // Cập nhật mức lương
    $salary->update([
        'name' => $validatedData['name'],
        'salaryCoefficient' => $validatedData['salaryCoefficient'],
        'monthlySalary' => $monthlySalary,
        'status' => $validatedData['status'], // Trạng thái true (1) hoặc false (0)
        'updated_by' => auth()->id(), // Cập nhật người thay đổi
    ]);

    // Chuyển hướng với thông báo thành công
    return redirect()->route('salary')->with('success', 'Cập nhật lương thành công.');
}

private function parseSalary($salary)
    {
        // Loại bỏ ₫ và dấu phẩy
        $salary = str_replace([',', '₫'], '', $salary);
        return (float) $salary; // Chuyển thành số thực để xử lý chính xác
    }
}