<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\User_attendanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TestExcelController;
use App\Exports\UsersExport;
use App\Http\Controllers\Caculate_Salary;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Salary_caculate;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SalaryLevelController;
use App\Http\Controllers\PayrollController;
use App\Models\Salary;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;

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

// Department routes (role = 1)
Route::middleware('auth')->group(function () {
    Route::get('/departments', [DepartmentController::class, 'allDepartment'])->name('departments');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{id}/members', [DepartmentController::class, 'showMembers'])->name('departments.show');
    Route::patch('/departments/{id}/update-status', [DepartmentController::class, 'updateStatus'])
        ->name('departments.updateStatus');
    Route::get('/departments/{id}/sub-departments', [DepartmentController::class, 'showSubDepartments'])->name('departments.subDepartments');
    Route::get('/departments/search', [DepartmentController::class, 'search'])->name('departments.search');
});

// User management routes
Route::middleware(['web', 'auth'])->group(function () {
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
    Route::get('/reminder-settings', [UserController::class, 'showReminderForm'])->name('reminder.settings');
    Route::post('/reminder-settings', [UserController::class, 'saveReminderSettings'])->name('reminder.save');
    Route::get('/setting/edit', [SettingController::class, 'edit'])->name('setting.edit');
    Route::post('/setting/update', [SettingController::class, 'update'])->name('setting.update');
    Route::post('/settings/update-reminder-time-checkout', [SettingController::class, 'updateReminderTimeCheckout'])->name('setting.updateReminderTimeCheckout');
});

// Attendance routes (role = 2)
Route::middleware('auth')->group(function () {
    Route::get('/attendance', [User_attendanceController::class, 'index'])->name('attendance');
    Route::post('/check-in', [User_attendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/check-out', [User_attendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::get('/attendance/monthly-report', [User_attendanceController::class, 'monthlyReport'])
        ->name('attendance.monthlyReport');
    Route::get('/attendance/allUser', [User_attendanceController::class, 'reportAllUsers'])->name('attendance.all');
    Route::get('/attendance/department-report', [User_attendanceController::class, 'departmentReport'])->name('department.report');
    Route::get('/attendance/search', [User_attendanceController::class, 'searchByDepartment'])->name('attendance.search');
    Route::post('/attendance/{id}/justification', [User_attendanceController::class, 'addJustification'])->name('attendance.addJustification');
    Route::get('/admin/manage-attendances', [User_attendanceController::class, 'manageInvalidAttendances'])->name('admin.manageAttendances');
    Route::post('/admin/approve-attendance/{id}', [User_attendanceController::class, 'approveAttendance'])->name('admin.approveAttendance');
    Route::post('/admin/reject-attendance/{id}', [User_attendanceController::class, 'rejectAttendance'])->name('admin.rejectAttendance');
});

Route::middleware('auth')->group(function () {
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
});
