<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    // Hiển thị tất cả phòng ban
    public function allDepartment()
    {
        if (Auth::user()->role == '2') {
            return redirect()->route('login')->with('error', 'Bạn không có quyền truy cập vào trang này.');
        }
        $departments = Department::where('parent_id', 0)
            ->with('children')
            ->get();
        return view('fe_department/departments', compact('departments'));
    }

    // Hiển thị danh sách thành viên của phòng ban
    public function showMembers($id)
    {
        $department = Department::with('children')->findOrFail($id);
        $departmentIds = collect([$department->id])->merge(
            $department->children->pluck('id')
        );

        $users = User::whereIn('department_id', $departmentIds)->get();
        return view('fe_department/department_members', compact('department', 'users'));
    }

    // Trang tạo phòng ban mới
    public function create()
    {
        $departments = Department::all();
        $childDepartments = [];
        return view('fe_department/create_department', compact('departments', 'childDepartments'));
    }

    // Lưu phòng ban mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:departments,id',
            'status' => 'required|boolean',
        ]);

        if ($validated['parent_id']) {
            $parentDepartment = Department::find($validated['parent_id']);
            if ($parentDepartment && !$parentDepartment->status) {
                return redirect()->back()->with('error', 'Không thể thêm phòng ban con vào một phòng ban không hoạt động.');
            }
        }

        $currentTime = now()->format('Y-m-d H:i:s');

        Department::create([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? 0,
            'status' => $validated['status'],
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ]);

        return redirect()->route('departments')->with('success', 'Đã thêm phòng ban thành công!');
    }

    // Trang sửa phòng ban
     // Hàm chỉnh sửa phòng ban
     public function edit($id)
     {
         $department = Department::findOrFail($id); // Tìm phòng ban theo ID
         $departments = Department::whereNull('parent_id')->get(); // Lấy các phòng ban cấp cha, không lấy tất cả
 
         return view('fe_department.edit_department', compact('department', 'departments'));
     }
 
     // Hàm cập nhật phòng ban
     public function update(Request $request, $id)
     {
         // Xác thực dữ liệu đầu vào
         $validated = $request->validate([
             'name' => 'required|string|max:255',
             'parent_id' => 'nullable|exists:departments,id', // Kiểm tra tồn tại của parent_id
             'status' => 'required|boolean',
         ]);
 
         // Tìm phòng ban theo ID
         $department = Department::findOrFail($id);
 
         // Kiểm tra nếu parent_id là phòng ban của chính nó (phòng ban không thể là cha của chính nó)
         if ($validated['parent_id'] == $id) {
             return redirect()->back()->with('error', 'Phòng ban không thể là cha của chính nó!');
         }
 
         // Cập nhật thông tin phòng ban
         $department->name = $validated['name'];
         $department->parent_id = $validated['parent_id'] ?? 0; // Nếu không có parent_id thì gán là 0
         $department->status = $validated['status'];
         $department->updated_by = auth()->id(); // Cập nhật người chỉnh sửa
         $department->save(); // Lưu vào cơ sở dữ liệu
 
         return redirect()->route('departments')->with('success', 'Cập nhật phòng ban thành công!');
     }
 
     // Hàm xóa phòng ban
     public function destroy($id)
     {
         // Tìm phòng ban theo ID
         $department = Department::findOrFail($id);
 
         // Kiểm tra nếu phòng ban có phòng ban con thì không cho phép xóa
         if (Department::where('parent_id', $id)->exists()) {
             return redirect()->route('departments')->with('error', 'Không thể xóa phòng ban vì có phòng ban con!');
         }
 
         // Xóa phòng ban
         $department->delete();
 
         return redirect()->route('departments')->with('success', 'Đã xóa phòng ban thành công!');
     }

    // Cập nhật trạng thái phòng ban
    public function updateStatus(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|boolean',
        ]);

        $department->status = $validated['status'];
        $department->updated_by = auth()->id();
        $department->updated_at = now();

        if (!$validated['status']) {
            $department->users()->update(['status' => false]);
        }

        $department->save();

        return redirect()
            ->route('departments.show', $id)
            ->with('success', 'Trạng thái phòng ban đã được cập nhật!');
    }

    // Hiển thị các phòng ban con của phòng ban đã chọn
    public function showSubDepartments($id)
    {
        $department = Department::with('children')->findOrFail($id);
        return view('fe_department/sub_departments', compact('department'));
    }

    // Hiển thị phòng ban và danh sách thành viên
    public function show($id)
    {
        $department = Department::with('users')->findOrFail($id);
        return view('fe_department/department', compact('department'));
    }

    // Tìm kiếm phòng ban theo tên
    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $departments = Department::where('name', 'LIKE', '%' . $validated['query'] . '%')
            ->with('children')
            ->get();

        return view('fe_department/departments', compact('departments'));
    }
}
