<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    protected $fillable = [
        'employee_id',
        'day',
        'shift_type',
        'start_time',
        'end_time',
        'start_time_2',
        'end_time_2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
