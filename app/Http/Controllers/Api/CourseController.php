<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Services\CourseManagementService;
use App\Services\CourseRatingService;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class CourseController extends Controller
{
    private CourseManagementService $courseService;
    private CourseRatingService $ratingService;
    private CourseRepositoryInterface $courseRepository;

    public function __construct(
        CourseManagementService $courseService,
        CourseRatingService $ratingService,
        CourseRepositoryInterface $courseRepository
    ) {
        $this->courseService = $courseService;
        $this->ratingService = $ratingService;
        $this->courseRepository = $courseRepository;
    }

    public function index(): JsonResponse
    {
        $courses = $this->courseRepository->getAll(15);
        
        return response()->json([
            'success' => true,
            'data' => $courses->items(),
            'meta' => [
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'per_page' => $courses->perPage(),
                'total' => $courses->total(),
            ],
        ]);
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        try {
            $course = $this->courseService->createCourse($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Course created successfully',
                'data' => $course->toArray(),
            ], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function show(int $id): JsonResponse
    {
        $course = $this->courseService->getCourse($id);
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }
        
        $rating = $this->ratingService->calculateAverageRating($id);
        
        return response()->json([
            'success' => true,
            'data' => array_merge($course->toArray(), [
                'rating' => $rating,
            ]),
        ]);
    }

    public function update(UpdateCourseRequest $request, int $id): JsonResponse
    {
        try {
            $course = $this->courseService->updateCourse($id, $request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully',
                'data' => $course->toArray(),
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getMessage() === 'Course not found' ? 404 : 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->courseService->deleteCourse($id);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete course',
                ], 500);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully',
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function published(): JsonResponse
    {
        $courses = $this->courseRepository->getPublished(15);
        
        return response()->json([
            'success' => true,
            'data' => $courses->items(),
            'meta' => [
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'per_page' => $courses->perPage(),
                'total' => $courses->total(),
            ],
        ]);
    }

    public function rating(int $id): JsonResponse
    {
        $course = $this->courseService->getCourse($id);
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }
        
        $rating = $this->ratingService->calculateAverageRating($id);
        
        return response()->json([
            'success' => true,
            'data' => $rating,
        ]);
    }
}