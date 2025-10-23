<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $fillable = [
        'question_id',
        'question_value',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
