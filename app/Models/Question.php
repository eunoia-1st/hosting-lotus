<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_category_id',
        'question_text',
        'question_type',
    ];

    public function question_options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }

    public function question_category()
    {
        return $this->belongsTo(QuestionCategory::class, 'question_category_id');
    }
}
