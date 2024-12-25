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
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search'); // Lấy từ khóa tìm kiếm từ request
        $query = User::with('department')->where('status', 1);  // Khởi tạo query chỉ lấy user có status = 1

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
        $userIds = $request->input('user_ids'); // Lấy danh sách ID nhân viên từ request

        if ($userIds) {
            // Lọc nhân viên không phải admin (role != 1) để vô hiệu hóa
            $usersToDisable = User::whereIn('id', $userIds)
                ->where('role', '!=', 1)
                ->get(); // Lấy danh sách nhân viên hợp lệ

            if ($usersToDisable->isEmpty()) {
                return redirect()->route('users')->with('error', 'Không thể xóa admin hoặc không có nhân viên hợp lệ để xóa.');
            }

            // Cập nhật trạng thái của tất cả nhân viên hợp lệ thành 0 (vô hiệu hóa)
            User::whereIn('id', $usersToDisable->pluck('id'))->update(['status' => 0]);

            return redirect()->route('users')->with('success', 'Xóa nhân viên thành công!');
        }

        return redirect()->route('users')->with('error', 'Vui lòng chọn nhân viên để xóa.');
    }
    private function getDepartments($parentId = 0)
    {
        return Department::where('parent_id', $parentId)->get();
    }

    public function create(Request $request)
    {
        $departments = Department::where('status', 1)->get(); // Lấy tất cả phòng ban còn hoạt động
        $salaries = SalaryLevel::where('is_active', 1)->get(); // Chỉ lấy các bậc lương đang hoạt động

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
            'department_id' => 'required_if:role,2,3|exists:departments,id',
            'role' => 'required|integer|in:1,2,3', // Đảm bảo trường role phải được nhập và hợp lệ
            'gender' => 'required|in:0,1',
            'date_of_birth' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            'salary_level_id' => 'required_if:role,2,3|exists:salary_level,id',

        ], [
            'email.regex' => 'Email không hợp lệ. Vui lòng nhập đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'phone_number.regex' => 'Số điện thoại phải bắt đầu bằng 0, 84, hoặc +84 và có 10 hoặc 11 chữ số.',
            'date_of_birth.before' => 'Nhân viên phải trên 18 tuổi.',
            'department_id.required_if' => 'Phòng ban là bắt buộc trừ khi chức vụ là Admin.',
            'salary_level_id.required_if' => 'Bậc lương là bắt buộc trừ khi chức vụ là Admin.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Kiểm tra xem nhân viên đã tồn tại chưa
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
            'role' => $request->role, // Gán role mặc định nếu không có
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'salary_level_id' => $request->salary_level_id,
        ]);

        return redirect()->route('users')->with('success', 'Nhân viên đã được tạo thành công.');
    }
    public function show($id)
    {
        $user = User::with(['department', 'salaryLevel'])->findOrFail($id); // Ensure salaryLevel is loaded
        $departments = Department::all(); // Lấy danh sách phòng ban
        $salaries = SalaryLevel::all();
        return view('fe_user.profile', compact('user', 'departments', 'salaries'));
    }

    public function edit(Request $request, $id)
    {
        $user = User::with('department')->findOrFail($id); // Lấy thông tin user cùng phòng ban

        // Lấy tất cả phòng ban cha (parent_id = 0)
        $departments = Department::where('parent_id', 0)->get();

        // Khởi tạo biến subDepartments là một mảng rỗng
        $subDepartments = [];

        // Nếu nhân viên đã chọn phòng ban cha, lấy phòng ban con
        if ($request->has('parent_department_id')) {
            $subDepartments = Department::where('parent_id', $request->input('parent_department_id'))->get();
        } elseif ($user->department && $user->department->parent_id) {
            $subDepartments = Department::where('parent_id', $user->department->parent_id)->get();
        }

        return view('fe_user.profile', compact('user', 'departments', 'subDepartments'));
    }
    // Cập nhật thông tin nhân viên
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:15',
            'gender' => 'required|in:0,1',
            'date_of_birth' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
        ], [
            'email.unique' => 'Email đã tồn tại.',
            'date_of_birth.before' => 'Nhân viên phải trên 18 tuổi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Cập nhật thông tin thất bại.');
        }

        $user->update($request->only([
            'name',
            'email',
            'phone_number',
            'gender',
            'date_of_birth'
        ]) + ['updated_by' => Auth::id()]);

        return redirect()->route('attendance')->with('success', 'Cập nhật thông tin thành công.');
    }

    // public function export() {
    //     $users = User::all(); // Lấy tất cả nhân viên
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

        $salaries = SalaryLevel::where('is_active', 1)->get(); // Only get active salary levels

        return view('fe_user/user_detail', compact('user', 'departments', 'subDepartments', 'salaryCoefficient', 'salaries'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:15',
            'department_id' => 'required_if:role,2,3|exists:departments,id',
            'status' => 'required|string',
            'salary_level_id' => 'required_if:role,2,3|exists:salary_level,id', // Xác thực salary_level_id
            'gender' => 'required|in:0,1',
            'date_of_birth' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            'role' => 'required|integer|in:1,2,3', // Ensure role is validated and within allowed values
        ], [
            'email.unique' => 'Email đã tồn tại.',
            'date_of_birth.before' => 'Nhân viên phải trên 18 tuổi.',
            'department_id.required_if' => 'Phòng ban là bắt buộc trừ khi chức vụ là Admin.',
            'salary_level_id.required_if' => 'Bậc lương là bắt buộc trừ khi chức vụ là Admin.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Cập nhật thông tin thất bại.');
        }

        $user->update($request->only([
            'name',
            'email',
            'phone_number',
            'department_id',
            'status',
            'salary_level_id',
            'gender',
            'date_of_birth',
            'role' // Ensure role is updated
        ]) + ['updated_by' => Auth::id()]);

        return redirect()->route('users.detail', ['id' => $user->id])
            ->with('success', 'Cập nhật thông tin nhân viên thành công.');
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
            // Check if any users were imported
            if (User::count() == 0) {
                return redirect()->route('users')->with('error', 'Import thất bại, không có dữ liệu được nhập.');
            }
            return redirect()->route('users')->with('success', 'Import thành công.');
        } catch (\Exception $e) {
            return redirect()->route('users')->with('error', 'Import thất bại, vui lòng kiểm tra lại file.');
        }
    }
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
    public function exportTemplate()
    {
        return Excel::download(new UserTemplateExport, 'import_template.xlsx');
    }

    public function showReminderForm()
    {
        $user = Auth::user(); // Lấy thông tin nhân viên hiện tại

        // Kiểm tra xem nhân viên đã đăng nhập chưa
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        return view('fe_attendances.users_attendance');  // Không cần truyền biến $user nữa
    }



    public function saveReminderSettings(Request $request)
    {
        // Kiểm tra nếu nhân viên đã đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // Lấy nhân viên hiện tại từ Auth
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

        // Lưu lại thông tin nhân viên
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

    public function UserchangePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'old_password.required' => 'Mật khẩu cũ là bắt buộc.',
            'new_password.required' => 'Mật khẩu mới là bắt buộc.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không trùng khớp.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.show', ['id' => $user->id])
                ->withErrors($validator)
                ->withInput()
                ->with('password_error', 'Đổi mật khẩu thất bại.');
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->route('users.show', ['id' => $user->id])
                ->with('password_error', 'Mật khẩu cũ không đúng.');
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('users.show', ['id' => $user->id])
            ->with('password_success', 'Mật khẩu đã được thay đổi thành công.');
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không trùng khớp.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.detail', ['id' => $user->id])
                ->withErrors($validator)
                ->withInput()
                ->with('password_error', 'Đổi mật khẩu thất bại.');
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('users.detail', ['id' => $user->id])
            ->with('password_success', 'Mật khẩu đã được thay đổi thành công.');
    }
}
