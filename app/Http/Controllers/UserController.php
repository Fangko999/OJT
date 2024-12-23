<?php

namespace App\Http\Controllers;

use Carbon\Carbon;  // Đảm bảo rằng bạn đã import Carbon

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exports\UsersExport;
use App\Exports\UserTemplateExport;
use App\Imports\UsersImport;
use App\Models\SalaryLevel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search'); // Lấy từ khóa tìm kiếm từ request
        $query = User::with('department');  // Khởi tạo query

        if ($search) {
            // Thêm điều kiện tìm kiếm theo tên, email hoặc tên phòng ban
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhereHas('department', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $users = $query->paginate(5); // Phân trang kết quả
        $departments = Department::all(); // Lấy tất cả phòng ban

        return view('fe_user/users', compact('users', 'departments', 'search'));
    }

    public function destroy(Request $request)
    {
        $userIds = $request->input('user_ids'); // Lấy danh sách ID người dùng từ request
    
        if ($userIds) {
            // Lọc người dùng không phải admin (role != 1) để vô hiệu hóa
            $usersToDisable = User::whereIn('id', $userIds)
                ->where('role', '!=', 1)
                ->get(); // Lấy danh sách người dùng hợp lệ
    
            if ($usersToDisable->isEmpty()) {
                return redirect()->route('users')->with('error', 'Không thể xóa admin hoặc không có người dùng hợp lệ để vô hiệu hóa.');
            }
    
            // Cập nhật trạng thái của tất cả người dùng hợp lệ thành 0 (vô hiệu hóa)
            User::whereIn('id', $usersToDisable->pluck('id'))->update(['status' => 0]);
    
            return redirect()->route('users')->with('success', 'Người dùng đã được vô hiệu hóa thành công.');
        }
    
        return redirect()->route('users')->with('error', 'Vui lòng chọn người dùng để vô hiệu hóa.');
    }
    private function getDepartments($parentId = 0)
    {
        return Department::where('parent_id', $parentId)->get();
    }

    public function create(Request $request)
    {
        $departments = Department::where('status', 1)->get(); // Lấy tất cả phòng ban còn hoạt động
        $salaries = SalaryLevel::all(); // Lấy tất cả bậc lương

        return view('fe_user.create_user', compact('departments', 'salaries'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'max:255',
                'unique:users,email',
            ],
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => [
                'required',
                'regex:/^(\+84|84|0)[0-9]{9}$/',
            ],
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|integer', // Đảm bảo trường role phải được nhập
            'gender' => 'required|in:0,1',
            'date_of_birth' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            'salary_level_id' => 'required|exists:salary_level,id',

        ], [
            'email.regex' => 'Email không hợp lệ. Vui lòng nhập đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'phone_number.regex' => 'Số điện thoại phải bắt đầu bằng 0, 84, hoặc +84 và có 10 hoặc 11 chữ số.',
            'date_of_birth.before' => 'Nhân viên phải trên 18 tuổi.',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        // Kiểm tra xem người dùng đã tồn tại chưa
        if (User::where('email', $request->email)->exists()) {
            return back()->with('error', 'Email đã tồn tại.')->withInput();
        }
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone_number' => $request->formatted_phone_number,
            'department_id' => $request->department_id,
            'status' => 1,
            'role' => $request->role , // Gán role mặc định nếu không có
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'salary_level_id' => $request->salary_level_id,
        ]);
    
        return redirect()->route('users')->with('success', 'Người dùng đã được tạo thành công.');
    }
    public function show($id)
    {
        $user = User::with('department')->findOrFail($id); // Lấy thông tin user cùng phòng ban
        $departments = Department::all(); // Lấy danh sách phòng ban

        return view('fe_user.profile', compact('user', 'departments'));
    }

    public function edit(Request $request, $id)
    {
        $user = User::with('department')->findOrFail($id); // Lấy thông tin user cùng phòng ban
    
        // Lấy tất cả phòng ban cha (parent_id = 0)
        $departments = Department::where('parent_id', 0)->get();
    
        // Khởi tạo biến subDepartments là một mảng rỗng
        $subDepartments = [];
    
        // Nếu người dùng đã chọn phòng ban cha, lấy phòng ban con
        if ($request->has('parent_department_id')) {
            $subDepartments = Department::where('parent_id', $request->input('parent_department_id'))->get();
        } elseif ($user->department && $user->department->parent_id) {
            $subDepartments = Department::where('parent_id', $user->department->parent_id)->get();
        }
    
        return view('fe_user/profile', compact('user', 'departments', 'subDepartments'));
    }
    // Cập nhật thông tin người dùng
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:15',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->only([
            'name',
            'email',
            'phone_number',
            'department_id'
        ]) + ['updated_by' => Auth::id()]);

        return redirect()->route('attendance')->with('success', 'User updated successfully.');
    }

    // public function export() {
    //     $users = User::all(); // Lấy tất cả người dùng
    //     dd($users); // Hiển thị dữ liệu
    //     return Excel::download(new UsersExport, 'users.xlsx');
    // }
    
    public function showDetail($id)
    {
        $user = User::with(['department', 'salaryLevel'])->findOrFail($id);
        $departments = Department::where('status', 1)->get(); // Lấy tất cả phòng ban còn hoạt động
        $salaries = SalaryLevel::all(); // Load all salary levels
    
        $subDepartments = $user->department && $user->department->parent_id 
            ? Department::where('parent_id', $user->department->parent_id)->get() 
            : [];
    
        return view('fe_user/user_detail', compact('user', 'departments', 'subDepartments', 'salaries'));
    }

    public function editUser(Request $request, $id)
    {
        $user = User::with(['department', 'salaryLevel'])->findOrFail($id);
        $departments = Department::where('parent_id', 0)->get();
    
        $subDepartments = [];
        $salaryCoefficient = $user->salaryLevel ? $user->salaryLevel->salary_coefficient : 1.00;
    
        if ($request->has('salary_level_id')) {
            $salaryLevel = SalaryLevel::find($request->input('salary_level_id'));
            $salaryCoefficient = $salaryLevel ? $salaryLevel->salary_coefficient : $salaryCoefficient;
        }
    
        return view('fe_user/user_detail', compact('user', 'departments', 'subDepartments', 'salaryCoefficient'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:15',
            'department_id' => 'required|exists:departments,id',
            'status' => 'required|string',
            'salary_level_id' => 'required|exists:salary_level,id', // Xác thực salary_level_id
            'gender' => 'required|in:0,1',
            'date_of_birth' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
        ], [
            'email.unique' => 'Email đã tồn tại.',
            'date_of_birth.before' => 'Nhân viên phải trên 18 tuổi.',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $user->update($request->only([
            'name', 'email', 'phone_number', 'department_id', 'status', 'salary_level_id', 'gender', 'date_of_birth'
        ]) + ['updated_by' => Auth::id()]);
    
        return redirect()->route('users.detail', ['id' => $user->id])
                         ->with('success', 'Thông tin người dùng đã được cập nhật thành công.');
    }
public function importPost(Request $request)
    {
        $request->validate([
            'import_file' => [
                'required',
                'file',
                'mimes:xls,xlsx'
            ],
        ]);
        try {
            Excel::import(new UsersImport, $request->file('import_file'));
            return redirect()->route('users')->with('success', 'Import thành công.');
        } catch (\Exception $e) {
            return redirect()->route('users')->with('error', 'Import thất bại. Vui lòng kiểm tra file mẫu.');
        }
    }
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
   public function exportTemplate(){
    return Excel::download(new UserTemplateExport, 'users_template.xlsx');
   }

   public function showReminderForm()
{
    $user = Auth::user(); // Lấy thông tin người dùng hiện tại

    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (!$user) {
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
    }

    return view('fe_attendances.users_attendance');  // Không cần truyền biến $user nữa
}



public function saveReminderSettings(Request $request)
{
    // Kiểm tra nếu người dùng đã đăng nhập
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
    }

    // Lấy người dùng hiện tại từ Auth
    $user = Auth::user();

    // Validate rằng remind_checkin và remind_checkout phải có định dạng H:i
    $request->validate([
        'remind_checkin' => 'required|date_format:H:i',
        'remind_checkout' => 'required|date_format:H:i',
    ]);

    // Lấy thời gian nhắc nhở từ form và thêm phần giây
    $remindCheckin = $request->input('remind_checkin') . ':00'; // Thêm phần giây
    $remindCheckout = $request->input('remind_checkout') . ':00'; // Thêm phần giây

    // Chuyển đổi thời gian thành định dạng H:i:s
    $user->remind_checkin = Carbon::createFromFormat('H:i:s', $remindCheckin)->format('H:i:s');
    $user->remind_checkout = Carbon::createFromFormat('H:i:s', $remindCheckout)->format('H:i:s');

    // Lưu lại thông tin người dùng
    if ($user->save()) {
        return redirect()->back()->with('success', 'Thời gian nhắc nhở đã được cập nhật!');
    } else {
        return back()->withErrors(['error' => 'Lỗi khi cập nhật thời gian nhắc nhở']);
    }
}

public function checkEmail(Request $request)
{
    $exists = User::where('email', $request->email)->exists();
    return response()->json(['exists' => $exists]);
}
}