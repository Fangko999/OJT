<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User_Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $leaveBalance = $user->leave_balance;
        return view('fe_leave.create', compact('leaveBalance'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Xác thực request
        $request->validate([
            'leave_type' => 'required|in:full_day,multiple_days',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date', // Quy tắc này chỉ áp dụng nếu cần
            'reason' => 'nullable|string|max:255',
        ]);

        if ($request->leave_type === 'multiple_days' && !$request->end_date) {
            return redirect()->back()->withErrors(['error' => 'Vui lòng chọn ngày kết thúc khi đăng ký nghỉ nhiều ngày.']);
        }

        if (in_array($request->leave_type, ['full_day']) && $request->end_date) {
            return redirect()->back()->withErrors(['error' => 'Bạn không cần chọn ngày kết thúc cho loại nghỉ này.']);
        }

        // Kiểm tra ngày nghỉ không được đăng ký trước ngày hiện tại (có thể điều chỉnh theo yêu cầu)
        // Kiểm tra ngày nghỉ không được đăng ký trước ngày hiện tại (trừ trường hợp nghỉ buổi sáng hoặc buổi chiều)
        if (!in_array($request->leave_type, ['full_day']) && strtotime($request->start_date) < strtotime('today')) {
            return redirect()->back()->withErrors(['error' => 'Bạn không thể đăng ký nghỉ cho ngày trước hiện tại.']);
        }

        // Tính toán số ngày nghỉ
        $duration = $request->leave_type === 'multiple_days'
            ? (strtotime($request->end_date) - strtotime($request->start_date)) / 86400 + 1
            : 1;

        // Kiểm tra ngày nghỉ có lương hay không
        $paidDays = min($duration, $user->leave_balance);
        $unpaidDays = $duration - $paidDays;

        // Tạo đơn nghỉ phép
        DB::table('leave_requests')->insert([
            'user_id' => $user->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?? $request->start_date,
            'leave_type' => $request->leave_type,
            'reason' => $request->reason,
            'duration' => $duration,
            'status' => 0, // Pending
            'is_paid' => $paidDays > 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Trừ số ngày nghỉ nếu đơn nghỉ có lương
        if ($paidDays > 0) {
            DB::table('users')
                ->where('id', $user->id)
                ->decrement('leave_balance', $paidDays);
        }

        return redirect()->route('leave_requests.index')->with('success', 'Đơn nghỉ phép đã được gửi thành công!');
    }

    public function index()
    {
        $leaveRequests = LeaveRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc') // Add this line to sort by creation date
            ->paginate(5);
        $remainingPaidLeaveDays = auth()->user()->remaining_paid_leave_days; // Assuming this attribute exists on the User model

        return view('fe_leave.leave_request', compact('leaveRequests', 'remainingPaidLeaveDays'));
    }

    public function destroy($id)
    {
        $leaveRequest = DB::table('leave_requests')->find($id);

        if (!$leaveRequest || $leaveRequest->user_id !== auth()->id()) {
            return redirect()->route('leave_requests.index')->withErrors('Không tìm thấy đơn nghỉ phép!');
        }

        // Chỉ cho phép xóa đơn nghỉ phép đang chờ duyệt
        if ($leaveRequest->status != 0) {
            return redirect()->route('leave_requests.index')->withErrors('Chỉ có thể xóa đơn nghỉ phép đang chờ duyệt!');
        }

        // Hoàn lại số ngày nghỉ có lương nếu đơn bị từ chối
        if ($leaveRequest->status == 2 && $leaveRequest->is_paid) {
            DB::table('users')
                ->where('id', $leaveRequest->user_id)
                ->increment('leave_balance', $leaveRequest->duration);
        }

        DB::table('leave_requests')->delete($id);

        return redirect()->route('leave_requests.index')->with('success', 'Đơn nghỉ phép đã được xóa!');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $leaveBalance = $user->leave_balance;

        // Lấy thông tin đơn nghỉ phép
        $leaveRequest = DB::table('leave_requests')
            ->where('id', $id)
            ->where('user_id', $user->id) // Đảm bảo chỉ chỉnh sửa đơn của chính mình
            ->first();

        if (!$leaveRequest) {
            return redirect()->route('leave_requests.index')->withErrors(['error' => 'Không tìm thấy đơn nghỉ phép.']);
        }

        // Chỉ cho phép chỉnh sửa đơn nghỉ phép đang chờ duyệt
        if ($leaveRequest->status != 0) {
            return redirect()->route('leave_requests.index')->withErrors(['error' => 'Chỉ có thể chỉnh sửa đơn nghỉ phép đang chờ duyệt!']);
        }

        return view('fe_leave.edit', compact('leaveRequest', 'leaveBalance'));
    }

    // Cập nhật đơn nghỉ phép
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        // Xác thực dữ liệu từ form
        $request->validate([
            'leave_type' => 'required|in:full_day,multiple_days',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        // Kiểm tra đơn có tồn tại và thuộc về người dùng
        $leaveRequest = DB::table('leave_requests')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$leaveRequest) {
            return redirect()->route('leave_requests.index')->withErrors(['error' => 'Không tìm thấy đơn nghỉ phép.']);
        }

        // Kiểm tra điều kiện logic
        if ($request->leave_type === 'multiple_days' && !$request->end_date) {
            return redirect()->back()->withErrors(['error' => 'Vui lòng chọn ngày kết thúc khi đăng ký nghỉ nhiều ngày.']);
        }

        if (in_array($request->leave_type, ['full_day']) && $request->end_date) {
            return redirect()->back()->withErrors(['error' => 'Bạn không cần chọn ngày kết thúc cho loại nghỉ này.']);
        }

        // Tính toán số ngày nghỉ
        $duration = $request->leave_type === 'multiple_days'
            ? (strtotime($request->end_date) - strtotime($request->start_date)) / 86400 + 1
            : 1;

        // Kiểm tra ngày nghỉ có lương hay không
        $paidDays = min($duration, $user->leave_balance);
        $unpaidDays = $duration - $paidDays;

        // Cập nhật dữ liệu vào database
        DB::table('leave_requests')
            ->where('id', $id)
            ->update([
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date ?? $request->start_date,
                'reason' => $request->reason,
                'duration' => $duration,
                'is_paid' => $paidDays > 0,
                'updated_at' => now(),
            ]);

        return redirect()->route('leave_requests.index')->with('success', 'Đơn nghỉ phép đã được cập nhật thành công!');
    }
}
