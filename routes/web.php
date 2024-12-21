<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\User_attendanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SalaryLevelController;
use App\Http\Controllers\PayrollController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\AdminLeaveRequestController;
use App\Http\Controllers\LeaveRequestController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('test-email', [HomeController::class, 'testmail']);

// Authentication routes
Route::middleware('web')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'loginPost']);
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    //     ->name('password.request');
    // Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    //     ->name('password.email');
});
Route::post('users/import/', [UserController::class, 'importPost'])->name('users.import');
Route::get('/export-users', [UserController::class, 'export'])->name('users.export');
Route::get('/export-template', [UserController::class, 'exportTemplate'])->name('export.template');

// Admin routes
Route::middleware(['web', 'auth', 'checkRole:1'])->group(function () {
    // Department routes
    Route::get('/departments', [DepartmentController::class, 'allDepartment'])->name('departments');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{id}/members', [DepartmentController::class, 'showMembers'])->name('departments.show');
    Route::patch('/departments/{id}/update-status', [DepartmentController::class, 'updateStatus'])->name('departments.updateStatus');
    Route::get('/departments/{id}/sub-departments', [DepartmentController::class, 'showSubDepartments'])->name('departments.subDepartments');
    Route::get('/departments/search', [DepartmentController::class, 'search'])->name('departments.search');
    Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::patch('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');

    // User management routes
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::post('/users/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/{id}/quick-update', [UserController::class, 'update'])->name('users.quickUpdate');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/detail', [UserController::class, 'showDetail'])->name('users.detail');
    Route::get('/users/{id}/edit', [UserController::class, 'editUser'])->name('users.edit');
    Route::post('/users/{id}/update-detail', [UserController::class, 'updateUser'])->name('users.updatedetail');
    Route::get('/attendance/department-report', [User_attendanceController::class, 'departmentReport'])->name('department.report');

    // Salary and payroll routes
    Route::get('/salaryLevels', [SalaryLevelController::class, 'index'])->name('salaryLevels');
    Route::get('/salaryLevels/create', [SalaryLevelController::class, 'create'])->name('salaryLevels.create');
    Route::post('/salaryLevels', [SalaryLevelController::class, 'store'])->name('salaryLevels.store');
    Route::get('/salaryLevels/{id}/edit', [SalaryLevelController::class, 'edit'])->name('salaryLevels.edit');
    Route::put('/salaryLevels/{id}', [SalaryLevelController::class, 'update'])->name('salaryLevels.update');
    Route::delete('/salaryLevels/soft-delete', [SalaryLevelController::class, 'softDeleteMultiple'])->name('salaryLevels.softDeleteMultiple');
    Route::get('/payroll/calculate', [PayrollController::class, 'showPayrollForm'])->name('payroll.form');
    Route::post('/payroll/calculate', [PayrollController::class, 'calculatePayroll'])->name('payroll.calculate');
    Route::post('/payroll/store', [PayrollController::class, 'storePayroll'])->name('payroll.store');
    Route::get('/payrolls', [PayrollController::class, 'showPayrolls'])->name('payrolls.index');
    Route::get('/run-payroll-calculate', function () {
        $exitCode = Artisan::call('payroll:calculate', ['--testTime' => '23:00:00']);
        return redirect()->back()->with('success', 'Tính lương cho tất cả nhân viên thành công!');
    })->name('calculateAll.payroll');

    // Chart routes
    Route::get('/chart', [ChartController::class, 'chartView'])->name('chart.view');
    Route::get('/api/user-count-by-department', [ChartController::class, 'getUserCountByDepartment']);
    Route::get('/employee-ratio', [ChartController::class, 'employeeRatioView'])->name('employee.ratio');
    Route::get('/api/gender-ratio-by-department/{departmentId}', [ChartController::class, 'getGenderRatioByDepartment']);
    Route::get('/gender-ratio', [ChartController::class, 'genderRatioView'])->name('gender.ratio');
    Route::get('/api/attendance-ratio', [ChartController::class, 'getAttendanceRatio'])->name('getAttendanceRatio');
    Route::get('/attendance-ratio', [ChartController::class, 'attendanceRatioView'])->name('attendance.ratio.view');
    Route::get('/age-ratio', [ChartController::class, 'ageRatioView'])->name('age.ratio');
    Route::get('/api/age-ratio-by-department/{departmentId}', [ChartController::class, 'getAgeRatioByDepartment']);
    Route::get('/salary-statistics', [ChartController::class, 'salaryStatisticsView'])->name('salary.statistics');
    Route::get('/api/salary-statistics-by-month', [ChartController::class, 'getSalaryStatisticsByMonth'])->name('salary.statistics.byMonth');
    Route::get('/seniority-ratio', [ChartController::class, 'seniorityRatioView'])->name('seniority.ratio');
    Route::get('/api/seniority-ratio', [ChartController::class, 'getSeniorityRatio'])->name('api.seniority.ratio');
    Route::get('/api/seniority-ratio-by-department/{departmentId?}', [ChartController::class, 'getSeniorityRatioByDepartment']);

    // Leave request management routes
    Route::get('/manage-leave_requests', [AdminLeaveRequestController::class, 'index'])->name('admin_leave_requests.index');
    Route::put('/manage-leave_requests/{id}/status', [AdminLeaveRequestController::class, 'updateStatus'])->name('leave_requests.updateStatus');

    Route::get('/admin/manage-attendances', [User_attendanceController::class, 'manageInvalidAttendances'])->name('admin.manageAttendances');
    Route::post('/admin/approve-attendance/{id}', [User_attendanceController::class, 'approveAttendance'])->name('admin.approveAttendance');
    Route::post('/admin/reject-attendance/{id}', [User_attendanceController::class, 'rejectAttendance'])->name('admin.rejectAttendance');
    Route::get('/setting/edit', [SettingController::class, 'edit'])->name('setting.edit');
    Route::post('/setting/update', [SettingController::class, 'update'])->name('setting.update');
    Route::post('/settings/update-reminder-time-checkout', [SettingController::class, 'updateReminderTimeCheckout'])->name('setting.updateReminderTimeCheckout');
});

// User routes
Route::middleware(['web', 'auth', 'checkRole:2'])->group(function () {
    // Attendance routes
    Route::get('/attendance', [User_attendanceController::class, 'index'])->name('attendance');
    Route::post('/check-in', [User_attendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/check-out', [User_attendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::get('/attendance/monthly-report', [User_attendanceController::class, 'monthlyReport'])->name('attendance.monthlyReport');
    Route::get('/attendance/allUser', [User_attendanceController::class, 'reportAllUsers'])->name('attendance.all');
    Route::get('/attendance/search', [User_attendanceController::class, 'searchByDepartment'])->name('attendance.search');
    Route::post('/attendance/{id}/justification', [User_attendanceController::class, 'addJustification'])->name('attendance.addJustification');
    Route::get('/reminder-settings', [UserController::class, 'showReminderForm'])->name('reminder.settings');
    Route::post('/reminder-settings', [UserController::class, 'saveReminderSettings'])->name('reminder.save');

    Route::post('/leave-request', [LeaveRequestController::class, 'store'])->name('leave.request');

    // quan li don xin nghi
    Route::get('/leave-requests/view', [LeaveRequestController::class, 'index'])->name('leave_requests.index');
    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave_requests.create');
    Route::get('/leave-requests/{id}/edit', [LeaveRequestController::class, 'edit'])->name('leave_requests.edit');
    Route::put('/leave-requests/{id}', [LeaveRequestController::class, 'update'])->name('leave_requests.update');
    Route::delete('/leave-requests/{id}', [LeaveRequestController::class, 'destroy'])->name('leave_requests.destroy');

});