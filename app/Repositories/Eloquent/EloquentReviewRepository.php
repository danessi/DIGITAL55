<?php

namespace App\Repositories\Eloquent;

use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EloquentReviewRepository implements ReviewRepositoryInterface
{
    public function getAverageRatingByCourse(int $courseId): float
    {
        $average = Review::where('course_id', $courseId)
            ->avg('rating');
        
        return round((float) $average, 2);
    }

    public function getTotalReviewsByCourse(int $courseId): int
    {
        return Review::where('course_id', $courseId)->count();
    }

    public function getBulkAverageRatings(array $courseIds): array
    {
        $ratings = Review::select('course_id', DB::raw('ROUND(AVG(rating), 2) as average_rating'))
            ->whereIn('course_id', $courseIds)
            ->groupBy('course_id')
            ->pluck('average_rating', 'course_id')
            ->toArray();
        
        $result = [];
        foreach ($courseIds as $courseId) {
            $result[$courseId] = $ratings[$courseId] ?? 0.0;
        }
        
        return $result;
    }
}