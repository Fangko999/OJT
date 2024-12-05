<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Setting;
use App\Models\User;
use App\Models\User_attendance;
use Carbon\Carbon;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class User_attendanceController extends Controller
{
    // Phương thức hiển thị lịch sử chấm công của người dùng
    public function index(Request $request)
    {
        $query = User_attendance::where('user_id', Auth::id());

        // Kiểm tra nếu có tham số tìm kiếm theo ngày
        if ($request->has('search_date') && $request->input('search_date') !== '') {
            $searchDate = $request->input('search_date');
            $query->whereDate('time', $searchDate); // Tìm kiếm theo ngày
        }

        $attendances = $query->orderBy('time', 'desc') // Sắp xếp theo thời gian mới nhất
            ->paginate(4); // Phân trang, mỗi trang có 7 bản ghi

        return view('fe_attendances.users_attendance', compact('attendances'));
    }


    // Phương thức check in


    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // Check if the user has already checked in today
        $todayCheckIn = User_attendance::where('user_id', $user->id)
            ->whereDate('time', $today)
            ->where('type', 'in')
            ->first();

        if ($todayCheckIn) {
            return redirect()->back()->with('error', 'Bạn đã check in hôm nay!');
        }

        // Lấy bản ghi check in mới nhất
        $latestAttendance = User_attendance::where('user_id', $user->id)
            ->orderBy('time', 'desc')
            ->first();

        // Kiểm tra trạng thái của bản ghi check in gần nhất
        if (!$latestAttendance || ($latestAttendance->type === 'out' || $latestAttendance->status)) {
            // Nếu không có bản ghi check in, hoặc bản ghi gần nhất là check out hoặc đã được chấp nhận

            // Kiểm tra xem hôm qua có quên check out không
            $yesterday = Carbon::yesterday()->toDateString();
            $yesterdayAttendance = User_attendance::where('user_id', $user->id)
                ->whereDate('time', $yesterday)
                ->where('type', 'in')
                ->whereNull('justification')
                ->first();

            // Nếu có bản ghi check in nhưng chưa có lý do giải trình, yêu cầu người dùng giải trình lý do
            if ($yesterdayAttendance) {
                return redirect()->back()->with('message', 'Bạn chưa checkout hôm qua, vui lòng giải trình lý do.');
            }

            // Nếu không có vấn đề gì, thực hiện check-in
            $TimeAttendent = Setting::first();
            $checkInTimeAllowed = $TimeAttendent->check_in_time;
            $checkInTime = now()->timezone('Asia/Ho_Chi_Minh');
            $validStatus = $checkInTime->format('H:i') < $checkInTimeAllowed;

            // Lưu bản ghi check-in
            $attendance = User_attendance::create([
                'time' => $checkInTime,
                'type' => 'in',
                'user_id' => $user->id,
                'status' => $validStatus,
                'justification' => $request->input('justification', ''),
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Nếu không hợp lệ và chưa có lý do giải trình, yêu cầu nhập lý do
            if (!$validStatus && !$request->input('justification')) {
                return redirect()->back()->with('warning', 'Check in không hợp lệ, vui lòng giải trình lý do.');
            }

            return redirect()->back()->with('message', 'Check in thành công.');
        } else {
            return redirect()->back()->with('message', 'Bạn đã Check In, vui lòng Check Out!');
        }
    }



    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // Check if the user has already checked out today
        $todayCheckOut = User_attendance::where('user_id', $user->id)
            ->whereDate('time', $today)
            ->where('type', 'out')
            ->first();

        if ($todayCheckOut) {
            return redirect()->back()->with('error', 'Bạn đã check out hôm nay!');
        }

        $latestAttendance = User_attendance::where('user_id', $user->id)
            ->orderBy('time', 'desc')
            ->first();

        if ($latestAttendance && $latestAttendance->type === 'in') {
            // Lấy thời gian check out
            $TimeAttendent = Setting::first();
            $checkOutTimeAllowed = $TimeAttendent->check_out_time;
            $checkOutTime = now()->timezone('Asia/Ho_Chi_Minh');
            $validStatus = $checkOutTime->format('H:i') >= $checkOutTimeAllowed;

            // Lưu bản ghi check-out bất kể hợp lệ hay không
            $attendance = User_attendance::create([
                'time' => $checkOutTime,
                'type' => 'out',
                'user_id' => $user->id,
                'status' => $validStatus,
                'justification' => $request->input('justification', ''), // Lưu lý do giải trình nếu có
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Nếu không hợp lệ và chưa có lý do giải trình, yêu cầu nhập lý do
            if (!$validStatus && !$request->input('justification')) {
                return redirect()->back()->with('warning', 'Check Out không hợp lệ, vui lòng giải trình lý do.');
            }

            // Thông báo cho người dùng về thời gian check-out
            return redirect()->back()->with('message', 'Check out thành công.');
        } else {
            return redirect()->back()->with('message', 'Bạn chưa Check In, không thể Check Out!');
        }
    }

    public function addJustification(Request $request, $attendanceId)
    {
        $attendance = User_attendance::findOrFail($attendanceId);

        // Lưu lý do giải trình
        $attendance->justification = $request->input('justification');

        // Cập nhật trạng thái lý do giải trình
        $attendance->status = 2; // Đang chờ giải trình
        $attendance->save();

        // Trả về thông báo thành công và chuyển hướng
        return redirect()->route('attendance')->with('message', 'Gửi giải trình thành công.');
    }

    public function approveAttendance($id)
    {
        $attendance = User_attendance::findOrFail($id);

        if ($attendance->status == 2) {
            $attendance->status = 1; // Chấp nhận lý do, chuyển thành hợp lệ
            $attendance->save();
            $reason = $attendance->justification;
            $user = $attendance->user;
            $name = $user->name;
            Mail::send('fe_email.accept_justification', compact('reason', 'name', 'attendance'), function ($email) use ($user) {
                $email->subject('Đơn giải trình của bạn đã được chấp nhận!');
                $email->to($user->email, $user->name);
            });
            return redirect()->back()->with('message', 'Đã chấp nhận lý do giải trình!');
        }

        return redirect()->back()->with('error', 'Không thể thay đổi trạng thái.');
    }

    public function rejectAttendance($id)
    {
        $attendance = User_attendance::findOrFail($id);

        if ($attendance->status == 2) {
            $attendance->status = 3; // Đã từ chối
            $attendance->save();
            $reason = $attendance->justification;
            $user = $attendance->user;
            $name = $user->name;
            Mail::send('fe_email.reject_justification', compact('reason', 'name', 'attendance'), function ($email) use ($user) {
                $email->subject('Đơn giải trình của bạn đã bị từ chối');
                $email->to($user->email, $user->name);
            });
            return redirect()->back()->with('message', 'Đã từ chối lý do giải trình!');
        }

        return redirect()->back()->with('error', 'Không thể thay đổi trạng thái.');
    }

    public function manageInvalidAttendances()
    {
        // Lấy tất cả các bản ghi không hợp lệ và sắp xếp theo cái mới nhất lên đầu
        $invalidAttendances = User_attendance::where('status', 2)
            ->orWhere('status', 2) // Include pending justifications
            ->orderBy('time', 'desc') // Sắp xếp theo thời gian mới nhất
            ->paginate(7); // Bạn có thể thay đổi số lượng trang

        return view('fe_attendances.attendance_management', compact('invalidAttendances'));
    }

    // Phương thức báo cáo hàng tháng
    public function monthlyReport(Request $request)
    {
        $userId = Auth::id();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $employeeData = [
            'name' => User::find($userId)->name,
            'position' => User::find($userId)->position,
            'attendance' => [],
        ];

        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            $attendances = User_attendance::where('user_id', $userId)
                ->whereDate('time', $date)
                ->orderBy('time')
                ->get();

            if ($attendances->isNotEmpty()) {
                foreach ($attendances as $attendance) {
                    $type = $attendance->type;

                    if (!isset($employeeData['attendance'][$date->toDateString()])) {
                        $employeeData['attendance'][$date->toDateString()] = [
                            'checkIn' => null,
                            'checkOut' => null,
                            'hours' => 0,
                        ];
                    }

                    if ($type === 'in') {
                        $employeeData['attendance'][$date->toDateString()]['checkIn'] = $attendance->time;
                    } elseif ($type === 'out') {
                        $checkInTime = $employeeData['attendance'][$date->toDateString()]['checkIn'];
                        if ($checkInTime !== null) {
                            $checkOutTime = $attendance->time;
                            $hoursWorked = Carbon::parse($checkInTime)->diffInHours(Carbon::parse($checkOutTime));
                            $employeeData['attendance'][$date->toDateString()]['checkOut'] = $checkOutTime;
                            $employeeData['attendance'][$date->toDateString()]['hours'] = $hoursWorked;
                        }
                    }
                }
            }
        }

        return view('fe_attendances.monthly_report', compact('employeeData', 'month', 'year'));
    }



    // Phương thức báo cáo cho phòng ban
    public function departmentReport(Request $request)
    {
        $departments = Department::where('parent_id', 0)->get();
        $selectedDepartmentIds = $request->input('department_ids', []);
        $selectedSubDepartment = $request->input('sub_department_id', '');

        $subDepartments = $selectedDepartmentIds
            ? Department::whereIn('parent_id', $selectedDepartmentIds)->get()
            : [];

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $singleDate = $request->input('single_date');

        $query = User_attendance::with('user')
            ->whereHas('user', function ($query) use ($selectedDepartmentIds, $selectedSubDepartment) {
                if ($selectedDepartmentIds) {
                    $query->whereIn('department_id', $selectedDepartmentIds);
                }
                if ($selectedSubDepartment) {
                    $query->orWhere('department_id', $selectedSubDepartment);
                }
            });

        // Áp dụng lọc ngày và phân trang
        $attendanceData = $this->filterByDate($query, $startDate, $endDate, $singleDate)
            ->orderBy('time', 'asc')
            ->paginate(7); // Chia thành 10 bản ghi mỗi trang
        // Rest of the monthly report logic
        $monthlyReport = [];
        foreach ($attendanceData as $attendance) {
            $userId = $attendance->user_id;
            $date = $attendance->time->format('Y-m-d');
            $type = $attendance->type;

            if (!isset($monthlyReport[$userId])) {
                $monthlyReport[$userId] = [
                    'name' => $attendance->user->name,
                    'position' => $attendance->user->position ?? 'N/A',
                    'dailyHours' => [],
                    'totalHours' => 0,
                ];
            }

            if (!isset($monthlyReport[$userId]['dailyHours'][$date])) {
                $monthlyReport[$userId]['dailyHours'][$date] = [];
            }

            if ($type === 'in') {
                $monthlyReport[$userId]['dailyHours'][$date][] = [
                    'checkIn' => $attendance->time,
                    'checkOut' => null,
                    'hours' => 0,
                ];
            } elseif ($type === 'out') {
                $lastIndex = count($monthlyReport[$userId]['dailyHours'][$date]) - 1;
                if ($lastIndex >= 0 && !$monthlyReport[$userId]['dailyHours'][$date][$lastIndex]['checkOut']) {
                    $checkInTime = Carbon::parse($monthlyReport[$userId]['dailyHours'][$date][$lastIndex]['checkIn']);
                    $checkOutTime = Carbon::parse($attendance->time);

                    $hoursWorked = $checkInTime->diffInHours($checkOutTime);
                    $monthlyReport[$userId]['dailyHours'][$date][$lastIndex]['checkOut'] = $attendance->time;
                    $monthlyReport[$userId]['dailyHours'][$date][$lastIndex]['hours'] = $hoursWorked;

                    $monthlyReport[$userId]['totalHours'] += $hoursWorked;
                }
            }
        }

        foreach ($monthlyReport as &$report) {
            foreach ($report['dailyHours'] as $dateHours) {
                foreach ($dateHours as $day) {
                    $report['totalHours'] += $day['hours'];
                }
            }
            $report['monthlyTotalHours'] = $report['totalHours'];
        }

        return view('fe_attendances.department_report', compact(
            'attendanceData',
            'departments',
            'subDepartments',
            'selectedDepartmentIds',
            'selectedSubDepartment',
            'monthlyReport',
            'startDate',
            'endDate',
            'singleDate'
        ));
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'time' => 'required|date_format:H:i',
            'type' => 'required|in:in,out',
            'status' => 'required|boolean',
            'justification' => 'nullable|string',
        ]);

        // Create a new attendance record
        $attendance = User_attendance::create([
            'user_id' => $validatedData['user_id'],
            'time' => strtotime($validatedData['time']),
            'type' => $validatedData['type'],
            'status' => false, // Mặc định là không hợp lệ
            'justification' => $validatedData['justification'],
            'created_by' => auth()->id(),
        ]);

        // Check the validity of the attendance record after creating
        $attendance->checkValidity();

        return redirect()->back()->with('success', 'Đã ghi log thành công.');
    }

    // Phương thức lọc theo ngày
    protected function filterByDate($query, $startDate, $endDate, $singleDate = null)
    {
        if ($singleDate) {
            // Nếu có ngày đơn lẻ, lọc theo ngày đó
            $query->whereDate('time', $singleDate);
        } else {
            // Nếu có khoảng thời gian, lọc theo khoảng thời gian
            if ($startDate) {
                $query->where('time', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('time', '<=', $endDate);
            }
        }
        return $query;
    }
}
