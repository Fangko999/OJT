<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Department;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'status',
        'department_id',
        'salary_level_id',
        'role',
        'remind_checkin',
        'remind_checkout',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'gender',
        'date_of_birth',
        'leave_balance',
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
    public function department() {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function isActive(){
        return $this->status === 1;
    }
    public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function updater()
{
    return $this->belongsTo(User::class, 'updated_by');
}
public function salaryLevel()
    {
        return $this->belongsTo(SalaryLevel::class);
    }

protected $attributes = [
        'remind_checkin' => '08:00:00',
        'remind_checkout' => '17:00:00',
];

public function getRoleNameAttribute()
    {
        switch ($this->role) {
            case 1:
                return 'Admin';
            case 2:
                return 'Nhân viên chính thức';
            case 3:
                return 'Nhân viên tạm thời';
            default:
                return 'Không xác định';
        }
    }

    public function getRemainingPaidLeaveDaysAttribute()
    {
        // Assuming you have a leave_balance column in your users table
        return $this->leave_balance;
    }

}
