<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $table = 'salaries';
    protected $fillable = [
        'name',
        'department_id',
        'salaryCoefficient',
        'monthlySalary',
        'status',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',  // Sử dụng Carbon cho các cột datetime
        'updated_at' => 'datetime',
    ];
       // Quan hệ với người tạo cấp bậc lương
       public function department()
       {
           return $this->belongsTo(Department::class, 'department_id');
       }
   
       // Quan hệ với người tạo
       public function creator()
       {
           return $this->belongsTo(User::class, 'created_by');
       }
   
       // Quan hệ với người cập nhật
       public function updater()
       {
           return $this->belongsTo(User::class, 'updated_by');
       }
}
