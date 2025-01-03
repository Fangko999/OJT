<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payrolls';
    protected $fillable = [
        'user_id',
        'month',
        'valid_days',
        'invalid_days',
        'salary_received',
        'salary_coefficient',
        'processed_by',
        'processed_at',
        'name_salary',
    ];

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getSalaryStatisticsByMonth($month)
    {
        return self::where('month', $month)
            ->select('user_id', 'salary_received')
            ->get();
    }
}
