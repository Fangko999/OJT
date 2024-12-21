<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
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
    public function showMembers($id)
    {
        // Lấy phòng ban hiện tại và các phòng ban con
        $department = Department::with('children')->findOrFail($id);
    
        // Lấy tất cả ID của phòng ban hiện tại và các phòng ban con
        $departmentIds = collect([$department->id])->merge(
            $department->children->pluck('id')
        );
    
        // Lấy tất cả người dùng có trạng thái hoạt động (status = 1) thuộc các phòng ban đó
        $users = User::whereIn('department_id', $departmentIds)
                     ->where('status', 1) // Chỉ lấy người dùng có trạng thái hoạt động
                     ->paginate(4); // Paginate users
    
        // Trả về view với dữ liệu phòng ban và danh sách người dùng
        return view('fe_department/department_members', compact('department', 'users'));
    }
    public function create()
    {
        // Lấy danh sách phòng ban có trạng thái hoạt động (status = 1)
        $departments = Department::where('status', 1)->get();
    
        // Khởi tạo danh sách tổ con nếu cần
        $childDepartments = []; 
    
        // Trả về view với danh sách phòng ban
        return view('fe_department/create_department', compact('departments', 'childDepartments'));
    }

    // Store the new department
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:departments,id',
            'status' => 'required|boolean',
        ]);
    
        // Check if there is a parent department and it is inactive
        if ($validated['parent_id']) {
            $parentDepartment = Department::find($validated['parent_id']);
            if ($parentDepartment && !$parentDepartment->status) {
                // Redirect back if the parent department is inactive
                return redirect()->back()->with('error', 'Không thể thêm phòng ban con vào một phòng ban không hoạt động.');
            }
        }
    
        $currentTime = now()->format('Y-m-d H:i:s');
    
        Department::create([
            'name' => $validated['name'],
            // Nếu không có parent_id thì gán về 0
            'parent_id' => $validated['parent_id'] ?? 0, // Gán parent_id là 0 nếu không có
            'status' => $validated['status'],
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ]);
    
        return redirect()->route('departments')->with('success', 'Department added successfully.');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('departments.all')->with('success', 'Department deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        // Tìm phòng ban theo ID, nếu không tìm thấy thì trả về lỗi 404.
        $department = Department::findOrFail($id);
    
        // Kiểm tra tính hợp lệ của dữ liệu đầu vào.
        $validated = $request->validate([
            'status' => 'required|boolean',
        ]);
    
        // Cập nhật trạng thái và thông tin metadata của phòng ban hiện tại.
        $department->status = $validated['status'];
        $department->updated_by = auth()->id();
        $department->updated_at = now();
    
        // Lưu phòng ban hiện tại.
        $department->save();
    
        if (!$validated['status']) {
            // Nếu phòng ban bị vô hiệu hóa -> vô hiệu hóa người dùng và các phòng ban con.
            $this->disableDepartmentAndChildren($department);
        } else {
            // Nếu phòng ban được kích hoạt -> kích hoạt lại người dùng trong phòng ban này.
            $this->enableUsersInDepartment($department);
        }
    
        // Trả về thông báo thành công.
        return redirect()
            ->route('departments.show', $id)
            ->with('success', 'Trạng thái phòng ban đã được cập nhật!');
    }
    
    /**
     * Vô hiệu hóa tất cả phòng ban con và người dùng bên trong nếu phòng ban cha bị vô hiệu hóa.
     */
    private function disableDepartmentAndChildren(Department $department)
    {
        // Vô hiệu hóa tất cả người dùng trong phòng ban hiện tại.
        $department->users()->update(['status' => false]);
    
        // Đệ quy: Vô hiệu hóa tất cả phòng ban con và người dùng bên trong.
        foreach ($department->children as $child) {
            $child->update(['status' => false]); // Cập nhật trạng thái phòng ban con.
            $this->disableDepartmentAndChildren($child); // Đệ quy cho các phòng ban con.
        }
    }
    
    /**
     * Kích hoạt lại tất cả người dùng trong phòng ban hiện tại.
     */
    private function enableUsersInDepartment(Department $department)
    {
        // Kích hoạt tất cả người dùng trong phòng ban này.
        $department->users()->update(['status' => true]);
    }
    
    public function showSubDepartments($id)
    {
        // Tìm phòng ban theo ID
        $department = Department::with('children')->findOrFail($id); // Giả sử 'children' là mối quan hệ trong model Department

        // Trả về view với thông tin phòng ban và các tổ con
        return view('fe_department/sub_departments', compact('department'));
    }

    public function show($id)
    {
        $department = Department::find($id);
        $users = User::where('department_id', $id)->paginate(5); // Paginate users
        return view('fe_department.department_members', compact('department', 'users'));
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|max:255',
        ]);

        // Tìm kiếm phòng ban theo tên
        $departments = Department::where('name', 'LIKE', '%' . $validated['query'] . '%')
            ->with('children') // Bao gồm các phòng ban con nếu cần
            ->get();

        return view('fe_department/departments', compact('departments'));
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $departments = Department::where('status', 1)->get();
        return view('fe_department/edit_department', compact('department', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:departments,id',
            'status' => 'required|boolean',
        ]);

        $department = Department::findOrFail($id);
        $department->name = $validated['name'];
        $department->parent_id = $validated['parent_id'] ?? 0;
        $department->status = $validated['status'];
        $department->updated_by = auth()->id();
        $department->updated_at = now();
        $department->save();

        return redirect()->route('departments.show', $id)->with('success', 'Department updated successfully.');
    }
}