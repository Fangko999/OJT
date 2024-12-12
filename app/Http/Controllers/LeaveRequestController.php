<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User_Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        // Xác thực request
        $request->validate([
            'leave_type' => 'required|in:full_day,multiple_days',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        if ($request->leave_type === 'multiple_days' && !$request->end_date) {
            return redirect()->back()->withErrors(['error' => 'Vui lòng chọn ngày kết thúc khi đăng ký nghỉ nhiều ngày.']);
        }

        if (in_array($request->leave_type, ['full_day']) && $request->end_date) {
            return redirect()->back()->withErrors(['error' => 'Bạn không cần chọn ngày kết thúc cho loại nghỉ này.']);
        }
        if (!in_array($request->leave_type, ['full_day']) && strtotime($request->start_date) < strtotime('today')) {
            return redirect()->back()->withErrors(['error' => 'Bạn không thể đăng ký nghỉ cho ngày trước hiện tại.']);
        }

        // Kiểm tra ngày nghỉ đã được nộp đơn chưa
        $existingLeaves = DB::table('leave_requests')
            ->where('user_id', $user->id)
            ->whereIn('status', [0, 1]) // Chỉ kiểm tra các đơn đang chờ phê duyệt hoặc đã được phê duyệt
            ->where(function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('start_date', '<=', $request->end_date ?? $request->start_date)
                             ->where('end_date', '>=', $request->start_date);
                });
            })
            ->get();

        if ($existingLeaves->isNotEmpty()) {
            $overlapMessages = [];
            foreach ($existingLeaves as $leave) {
                if ($leave->start_date == $leave->end_date) {
                    $overlapMessages[] = "Ngày " . date('d-m-Y', strtotime($leave->start_date)) . " đã được nộp đơn xin nghỉ.";
                } else {
                    $overlapMessages[] = "Khoảng ngày từ " . date('d-m-Y', strtotime($leave->start_date)) . " đến " . date('d-m-Y', strtotime($leave->end_date)) . " đã được nộp đơn xin nghỉ.";
                }
            }
            return redirect()->back()->withErrors(['error' => implode(' ', $overlapMessages)]);
        }

        // Kiểm tra ngày nghỉ có lương hay không
        $startMonth = date('Y-m', strtotime($request->start_date));
        $endMonth = date('Y-m', strtotime($request->end_date ?? $request->start_date));

        $currentMonth = $startMonth;
        $isPaid = true;

        while (strtotime($currentMonth) <= strtotime($endMonth)) {
            $paidLeaveCount = DB::table('leave_requests')
                ->where('user_id', $user->id)
                ->where('is_paid', true)
                ->where('status', 1) // Chỉ kiểm tra các đơn đã được chấp nhận
                ->where('start_date', 'like', "$currentMonth%")
                ->count();

            if ($paidLeaveCount >= 1) {
                $isPaid = false;
                break;
            }

            $currentMonth = date('Y-m', strtotime("+1 month", strtotime($currentMonth)));
        }

        // Tính tổng số ngày nghỉ
        $startDate = strtotime($request->start_date);
        $endDate = strtotime($request->end_date ?? $request->start_date);
        $totalDays = ($endDate - $startDate) / (60 * 60 * 24) + 1;

        $paidDays = $isPaid ? $totalDays : 0;
        $unpaidDays = $isPaid ? 0 : $totalDays;

        // Tạo đơn nghỉ phép
        DB::table('leave_requests')->insert([
            'user_id' => $user->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?? $request->start_date,
            'leave_type' => $request->leave_type,
            'reason' => $request->reason,
            'status' => 0, // Pending
            'is_paid' => $isPaid,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', "Đơn nghỉ phép đã được gửi thành công! Số ngày nghỉ có lương: $paidDays, số ngày nghỉ không lương: $unpaidDays");
    }

    public function index()
    {
        $user = auth()->user();
        $userId = auth()->id();
        $leaveRequests = DB::table('leave_requests')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // dd($leaveRequests); // Kiểm tra cấu trúc dữ liệu
        return view('fe_leave/leave_request', compact('leaveRequests'));
    }


    public function destroy($id)
    {
        $leaveRequest = DB::table('leave_requests')->find($id);

        if (!$leaveRequest || $leaveRequest->user_id !== auth()->id()) {
            return redirect()->route('fe_leave/leave_request')->withErrors('Không tìm thấy đơn nghỉ phép!');
        }

        DB::table('leave_requests')->delete($id);

        return redirect()->route('fe_leave/leave_request')->with('success', 'Đơn nghỉ phép đã được xóa!');
    }

    public function edit($id)
    {
        $user = auth()->user();

        // Lấy thông tin đơn nghỉ phép
        $leaveRequest = DB::table('leave_requests')
            ->where('id', $id)
            ->where('user_id', $user->id) // Đảm bảo chỉ chỉnh sửa đơn của chính mình
            ->first();

        if (!$leaveRequest) {
            return redirect()->route('fe_leave/leave_request')->withErrors(['error' => 'Không tìm thấy đơn nghỉ phép.']);
        }

        return view('fe_leave/edit', compact('leaveRequest'));
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
            return redirect()->route('fe_leave/leave_request')->withErrors(['error' => 'Không tìm thấy đơn nghỉ phép.']);
        }

        // Kiểm tra điều kiện logic
        if ($request->leave_type === 'multiple_days' && !$request->end_date) {
            return redirect()->back()->withErrors(['error' => 'Vui lòng chọn ngày kết thúc khi đăng ký nghỉ nhiều ngày.']);
        }

        if (in_array($request->leave_type, ['full_day']) && $request->end_date) {
            return redirect()->back()->withErrors(['error' => 'Bạn không cần chọn ngày kết thúc cho loại nghỉ này.']);
        }

        // Cập nhật dữ liệu vào database
        DB::table('leave_requests')
            ->where('id', $id)
            ->update([
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date ?? $request->start_date,
                'reason' => $request->reason,
                'updated_at' => now(),
            ]);

        return redirect()->route('fe_leave/leave_request')->with('success', 'Đơn nghỉ phép đã được cập nhật thành công!');
    }
}
