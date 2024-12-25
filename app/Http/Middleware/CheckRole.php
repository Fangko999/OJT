<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $roles)
    {
        \Log::info('CheckRole middleware executed.');

        if (!Auth::check()) {
            // Nếu người dùng chưa đăng nhập, chuyển hướng về login
            return redirect('/login')->with('error', 'Bạn cần đăng nhập để truy cập.');
        }

        // Lấy vai trò của người dùng hiện tại
        $userRole = Auth::user()->role;

        // Tách danh sách các vai trò truyền vào (chuỗi) thành mảng
        $rolesArray = explode(',', $roles);

        // Debugging: Log the roles and user role
        \Log::info('User Role: ' . $userRole);
        \Log::info('Allowed Roles: ' . implode(', ', $rolesArray));

        // Kiểm tra nếu vai trò của người dùng không nằm trong danh sách
        if (!in_array($userRole, $rolesArray)) {
            return redirect('/login')->with('error', 'Bạn không có quyền truy cập vào trang này.');
        }

        // Nếu vai trò hợp lệ, tiếp tục request
        return $next($request);
    }
}
