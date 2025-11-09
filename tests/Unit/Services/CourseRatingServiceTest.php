<?php

namespace Tests\Unit\Services;

use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Services\CourseRatingService;
use Mockery;
use Tests\TestCase;

class CourseRatingServiceTest extends TestCase
{
    private $reviewRepository;
    private CourseRatingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->reviewRepository = Mockery::mock(ReviewRepositoryInterface::class);
        $this->service = new CourseRatingService($this->reviewRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_calculates_average_rating_correctly(): void
    {
        $courseId = 1;
        
        $this->reviewRepository
            ->shouldReceive('getAverageRatingByCourse')
            ->once()
            ->with($courseId)
            ->andReturn(4.5);
            
        $this->reviewRepository
            ->shouldReceive('getTotalReviewsByCourse')
            ->once()
            ->with($courseId)
            ->andReturn(100);

        $result = $this->service->calculateAverageRating($courseId);

        $this->assertEquals(4.5, $result['average_rating']);
        $this->assertEquals(100, $result['total_reviews']);
        $this->assertStringContainsString('100 reviews', $result['rating_display']);
    }

    public function test_handles_course_with_no_reviews(): void
    {
        $courseId = 1;
        
        $this->reviewRepository
            ->shouldReceive('getAverageRatingByCourse')
            ->once()
            ->with($courseId)
            ->andReturn(0.0);
            
        $this->reviewRepository
            ->shouldReceive('getTotalReviewsByCourse')
            ->once()
            ->with($courseId)
            ->andReturn(0);

        $result = $this->service->calculateAverageRating($courseId);

        $this->assertEquals(0.0, $result['average_rating']);
        $this->assertEquals(0, $result['total_reviews']);
        $this->assertEquals('No reviews yet', $result['rating_display']);
    }

    public function test_calculates_bulk_ratings(): void
    {
        $courseIds = [1, 2, 3];
        $expectedRatings = [
            1 => 4.5,
            2 => 3.8,
            3 => 5.0,
        ];
        
        $this->reviewRepository
            ->shouldReceive('getBulkAverageRatings')
            ->once()
            ->with($courseIds)
            ->andReturn($expectedRatings);

        $result = $this->service->calculateBulkRatings($courseIds);

        $this->assertEquals($expectedRatings, $result);
    }
}