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
use App\Models\Salary;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search'); // Lấy từ khóa tìm kiếm từ request
        $query = User::with('department');  // Khởi tạo query

        if ($search) {
            // Thêm điều kiện tìm kiếm theo tên, email hoặc chức vụ
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('position', 'LIKE', "%{$search}%");
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
        $departments = $this->getDepartments(); // Lấy phòng ban cha

        // Lấy phòng ban con nếu đã chọn phòng ban cha
        $subDepartments = $request->has('parent_id') ? $this->getDepartments($request->input('parent_id')) : [];

        return view('fe_user.create_user', compact('departments', 'subDepartments'));
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
                'regex:/^84[0-9]{9,10}$/',
            ],
            'position' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|integer', // Đảm bảo trường role phải được nhập

        ], [
            'email.regex' => 'Email không hợp lệ. Vui lòng nhập đúng định dạng.',
            'phone_number.regex' => 'Số điện thoại phải bắt đầu bằng 84 và có 9-10 chữ số.',
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
            'phone_number' => $request->phone_number,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'status' => 1,
            'role' => $request->role , // Gán role mặc định nếu không có
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
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
            'position' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->only([
            'name',
            'email',
            'phone_number',
            'position',
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
        $user = User::with(['department', 'salary'])->findOrFail($id);
        $departments = Department::where('parent_id', 0)->get();
        $salaries = Salary::all(); // Lấy tất cả hệ số lương
    
        $subDepartments = $user->department && $user->department->parent_id 
            ? Department::where('parent_id', $user->department->parent_id)->get() 
            : [];
    
        return view('fe_user/user_detail', compact('user', 'departments', 'subDepartments', 'salaries'));
    }

    public function editUser(Request $request, $id)
    {
        $user = User::with(['department', 'salaries'])->findOrFail($id);
        $departments = Department::where('parent_id', 0)->get();
    
        $subDepartments = [];
        $salaryCoefficient = $user->salary ? $user->salary->salaryCoefficient : 1.00;
    
        if ($request->has('salary_id')) {
            $salary =Salary::find($request->input('salary_id'));
            $salaryCoefficient = $salary ? $salary->salaryCoefficient : $salaryCoefficient;
        }
    
        return view('fe_user/user_detail', compact('user', 'departments', 'subDepartments', 'salaryCoefficient'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:15',
            'position' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'status' => 'required|string',
            'salary_id' => 'required|exists:salaries,id', // Xác thực salary_id
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $user->update($request->only([
            'email', 'phone_number', 'position', 'department_id', 'status', 'salary_id'
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

    // Validate rằng reminder_time phải có định dạng H:i
    $request->validate([
        'reminder_time' => 'required|date_format:H:i',
    ]);

    // Lấy thời gian nhắc nhở từ form và thêm phần giây
    $reminderTime = $request->input('reminder_time') . ':00'; // Thêm phần giây

    // Chuyển đổi thời gian thành định dạng H:i:s
    $user->reminder_time = Carbon::createFromFormat('H:i:s', $reminderTime)->format('H:i:s');

    // Lưu lại thông tin người dùng
    if ($user->save()) {
        return redirect()->back()->with('success', 'Thời gian nhắc nhở đã được cập nhật!');
    } else {
        return back()->withErrors(['error' => 'Lỗi khi cập nhật thời gian nhắc nhở']);
    }
}


   

   
}
