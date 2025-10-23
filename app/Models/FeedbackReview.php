<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackReview extends Model
{
    protected $fillable = [
        'admin_id',
        'feedback_id',
        'tag',
        'comment'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_id');
    }
}
