<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class AdminLeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());
        $query = LeaveRequest::with(['user']);

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', intval($request->status));
        }

        // Lọc theo nhân viên
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(7);

        return view('fe_leave/admin_leaveRequest', compact('leaveRequests'));
    }





    public function updateStatus(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'status' => 'required|in:1,2', // Chỉ chấp nhận giá trị số 1 (chấp nhận) và 2 (từ chối)
        ]);

        $leaveRequest = LeaveRequest::findOrFail($id);

        // Cập nhật trạng thái
        $leaveRequest->status = intval($request->status); // Đảm bảo `status` là số
        $leaveRequest->save();

        return redirect()->route('leave_requests.index')
            ->with('success', 'Cập nhật trạng thái thành công!');
    }
}
