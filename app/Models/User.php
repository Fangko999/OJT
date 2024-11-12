<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Department;
use App\Models\SalaryLevel;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The name of the table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'status',
        'position',
        'department_id',
        'role',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'salary_level_id', // Thêm cột này để quản lý cấp bậc lương
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with Department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Relationship with SalaryLevel.
     */
    public function salaryLevel()
    {
        return $this->belongsTo(salary_level::class, 'salary_level_id', 'id');
    }

    /**
     * Accessor to get the user's salary from salary_level.
     *
     * @return float|null
     */
    public function getSalaryAttribute()
    {
        return $this->salaryLevel->monthly_salary ?? null;
    }

    /**
     * Check if the user is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 1;
    }
}
