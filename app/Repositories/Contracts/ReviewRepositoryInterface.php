<?php

namespace App\Repositories\Contracts;

interface ReviewRepositoryInterface
{
    public function getAverageRatingByCourse(int $courseId): float;
    
    public function getTotalReviewsByCourse(int $courseId): int;
    
    public function getBulkAverageRatings(array $courseIds): array;
}