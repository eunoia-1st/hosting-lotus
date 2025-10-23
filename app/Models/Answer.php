<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'feedback_id',
        'question_id',
        'option_id',
        'answer_text'
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function question_option()
    {
        return $this->belongsTo(QuestionOption::class, 'option_id');
    }
}
