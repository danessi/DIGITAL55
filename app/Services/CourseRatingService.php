<?php

namespace App\Services;

use App\Repositories\Contracts\ReviewRepositoryInterface;

class CourseRatingService
{
    private ReviewRepositoryInterface $reviewRepository;

    public function __construct(ReviewRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function calculateAverageRating(int $courseId): array
    {
        $average = $this->reviewRepository->getAverageRatingByCourse($courseId);
        $total = $this->reviewRepository->getTotalReviewsByCourse($courseId);
        
        return [
            'average_rating' => $average,
            'total_reviews' => $total,
            'rating_display' => $this->formatRatingDisplay($average, $total),
        ];
    }

    public function calculateBulkRatings(array $courseIds): array
    {
        return $this->reviewRepository->getBulkAverageRatings($courseIds);
    }

    private function formatRatingDisplay(float $rating, int $totalReviews): string
    {
        if ($totalReviews === 0) {
            return 'No reviews yet';
        }
        
        $stars = str_repeat('★', (int) round($rating));
        $emptyStars = str_repeat('☆', 5 - (int) round($rating));
        
        return sprintf('%s%s (%s) - %d reviews', $stars, $emptyStars, number_format($rating, 1), $totalReviews);
    }
}