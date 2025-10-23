<?php

namespace App\Observers;

use App\Models\QuestionCategory;
use App\Jobs\ActivateCategoryJob;

class QuestionCategoryObserver
{
    public function created(QuestionCategory $category): void
    {
        if ($category->status === 'pending') {
            // Delay 1 menit untuk development
            ActivateCategoryJob::dispatch($category->id)->delay(now()->addMinute());
        }
    }
}
