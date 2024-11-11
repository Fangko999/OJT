<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salary_level extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'salary_level';

    // Các cột có thể mass-assigned
    protected $fillable = [
        'name',
        'monthly_salary',
        'daily_salary',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    // Cấu hình các cột ngày tháng
    protected $casts = [
        'created_at' => 'datetime', // Sử dụng Carbon cho các cột datetime
        'updated_at' => 'datetime',
    ];

    // Quan hệ với người tạo cấp bậc lương
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Quan hệ với người cập nhật cấp bậc lương
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Tắt tính năng timestamps nếu không sử dụng
    public $timestamps = false; // Bỏ đi nếu bạn đang sử dụng các cột created_at và updated_at tự động quản lý
}
