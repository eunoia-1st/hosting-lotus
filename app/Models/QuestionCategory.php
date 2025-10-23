<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionCategory extends Model
{
    protected $fillable = [
        'name',
        'status'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'question_category_id');
    }

    public function isActiveHours(): bool
    {
        $now = now()->format('H:i'); // format jam:menit
        return $now >= '10:00' && $now <= '23:00';
    }

    public function updateStatusByTime()
    {
        if ($this->status === 'pending' && $this->isActiveHours()) {
            $this->update(['status' => 'active']);
        }
    }
}
