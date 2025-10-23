<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFeedback extends Model
{
    protected $table = 'employee_feedbacks';

    protected $fillable = [
        'employee_id',
        'feedback_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }
}
