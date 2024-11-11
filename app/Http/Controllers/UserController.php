<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exports\UsersExport;
use App\Exports\UserTemplateExport;
use App\Imports\UsersImport;
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

        $users = $query->paginate(7); // Phân trang kết quả
        $departments = Department::all(); // Lấy tất cả phòng ban

        return view('fe_user.users', compact('users', 'departments', 'search'));
    }

    public function destroy(Request $request)
    {
        $userIds = $request->input('user_ids'); // Lấy danh sách ID người dùng từ request

        if ($userIds) {
            // Lọc ra các user không phải admin (role != 1) để xóa
            $usersToDelete = User::whereIn('id', $userIds)
                ->where('role', '!=', 1)
                ->pluck('id'); // Lấy danh sách ID hợp lệ

            if ($usersToDelete->isEmpty()) {
                return redirect()->route('users')->with('error', 'Không thể xóa admin hoặc không có nhân viên hợp lệ để xóa.');
            }

            User::destroy($usersToDelete); // Xóa người dùng hợp lệ

            return redirect()->route('users')->with('success', 'Xóa nhân viên thành công !');
        }

        return redirect()->route('users')->with('error', 'Vui lòng chọn nhân viên để xóa.');
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
                'string',
                'regex:/^(\+84|84|0)(\d{9})$/', // Cho phép các định dạng như +84, 84, 0 và 9 số phía sau
            ],
            'position' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|integer', // Đảm bảo trường role phải được nhập
        ], [
            'email.regex' => 'Email không hợp lệ. Vui lòng nhập đúng định dạng.',
            'phone_number.regex' => 'Số điện thoại phải bắt đầu bằng +84, 84 hoặc 0 và có 10 đên 11 số!',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Chuẩn hóa số điện thoại
        $phone_number = $this->normalizePhoneNumber($request->phone_number);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone_number' => $phone_number, // Lưu số điện thoại đã chuẩn hóa
            'department_id' => $request->department_id,
            'position' => $request->position,
            'status' => 1,
            'role' => $request->role, // Gán role mặc định nếu không có
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('users')->with('success', 'Tạo nhân viên thành công.');
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

        return view('fe_user.profile', compact('user', 'departments', 'subDepartments'));
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone_number' => [
                'required',
                'string',
                'regex:/^(\+84|84|0)(\d{9})$/', // Kiểm tra định dạng số điện thoại
            ],
            'position' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Chuẩn hóa số điện thoại
        $phone_number = $this->normalizePhoneNumber($request->phone_number);

        $user->update($request->only([
            'name',
            'email',
            'position',
            'department_id'
        ]) + [
            'phone_number' => $phone_number, // Lưu số điện thoại đã chuẩn hóa
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('users')->with('success', 'Cập nhật thông tin nhân viên thành công!');
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
            return redirect()->route('users')->with('success', 'Thêm nhân viên thành công.');
        } catch (\Exception $e) {
            return redirect()->route('users')->with('error', 'Thêm thất bại, vui lòng kiểm tra lại.');
        }
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'Users.xlsx');
    }

    public function exportTemplate()
    {
        return Excel::download(new UserTemplateExport, 'Template-User.xlsx');
    }

    // Phương thức chuẩn hóa số điện thoại
    private function normalizePhoneNumber($phone)
    {
        // Loại bỏ khoảng trắng và ký tự không phải số
        $phone = preg_replace('/\D/', '', $phone);

        // Nếu số điện thoại bắt đầu bằng 84, thì thay đổi thành 0
        if (substr($phone, 0, 2) === '84') {
            $phone = '0' . substr($phone, 2);
        } elseif (substr($phone, 0, 1) === '0') {
            // Nếu bắt đầu bằng 0 thì giữ nguyên
        } else {
            // Nếu không bắt đầu bằng +84 hoặc 0, thêm 0 ở đầu
            $phone = '0' . $phone;
        }

        return $phone;
    }
}
