<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // <-- TAMBAHKAN INI

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'seat_id',
        'customer_id'
    ];

    // ... (semua relasi Anda tetap sama) ...

    public function answers()
    {
        return $this->hasMany(Answer::class, 'feedback_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_feedbacks')
            ->with('employee_shifts');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    // --- INI ADALAH METHOD YANG DIPERBAIKI ---
    public function getWorkingEmployeesAttribute()
    {
        $feedbackTime = Carbon::parse($this->created_at);
        $feedbackDay = strtolower($feedbackTime->format('l'));

        // Buat objek Carbon hanya untuk waktunya saja
        $timeToCheck = Carbon::parse($feedbackTime->format('H:i:s'));

        return $this->employees->filter(function ($employee) use ($feedbackDay, $timeToCheck) {

            $shift = $employee->employee_shifts->firstWhere('day', $feedbackDay);

            if (!$shift) {
                return false;
            }

            $inFirstShift = false;
            if ($shift->start_time && $shift->end_time) {
                // Konversi waktu shift menjadi objek Carbon
                $startTime1 = Carbon::parse($shift->start_time);
                $endTime1 = Carbon::parse($shift->end_time);
                // Gunakan method between() yang lebih aman
                $inFirstShift = $timeToCheck->between($startTime1, $endTime1, true);
            }

            $inSecondShift = false;
            if ($shift->start_time_2 && $shift->end_time_2) {
                // Konversi waktu shift 2 menjadi objek Carbon
                $startTime2 = Carbon::parse($shift->start_time_2);
                $endTime2 = Carbon::parse($shift->end_time_2);
                // Gunakan method between() yang lebih aman
                $inSecondShift = $timeToCheck->between($startTime2, $endTime2, true);
            }

            return $inFirstShift || $inSecondShift;
        });
    }
}
