<?php

namespace App\Jobs;

use App\Models\QuestionCategory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class ActivateCategoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $categoryId;

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function handle(): void
    {
        $category = QuestionCategory::find($this->categoryId);
        if (!$category || $category->status !== 'pending') return;

        $now = Carbon::now();
        $targetTime = Carbon::today()
            ->setHour(config('questionCategory.active_hour')) // jam global dari config
            ->setMinute(config('questionCategory.active_minute', 0)) // menit optional
            ->setSecond(0);

        // Jika sudah melewati jam target → aktifkan langsung
        if ($now->greaterThanOrEqualTo($targetTime)) {
            $category->update(['status' => 'active']);
            return;
        }

        // Delay sampai jam target, dispatch sekali saja
        self::dispatch($this->categoryId)->delay($targetTime);
    }

    public function middleware()
    {
        return [new \Illuminate\Queue\Middleware\WithoutOverlapping($this->categoryId)];
    }
}
