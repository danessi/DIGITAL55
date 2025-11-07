<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InstructorService;
use Illuminate\Http\JsonResponse;

class InstructorController extends Controller
{
    private InstructorService $instructorService;

    public function __construct(InstructorService $instructorService)
    {
        $this->instructorService = $instructorService;
    }

    public function index(): JsonResponse
    {
        $startTime = microtime(true);
        
        $instructors = $this->instructorService->getAllInstructorsOptimized();
        
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
        return response()->json([
            'success' => true,
            'data' => $instructors,
            'meta' => [
                'total' => $instructors->count(),
                'execution_time_ms' => $executionTime,
                'optimizations' => [
                    'cursor_pagination' => true,
                    'lazy_loading' => true,
                    'redis_cache' => true,
                    'selective_columns' => true,
                ],
                'cache_info' => [
                    'cached' => cache()->has('instructors:all:optimized'),
                    'ttl_seconds' => 3600,
                ],
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $instructor = $this->instructorService->getInstructor($id);
        
        if (!$instructor) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor not found',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $instructor,
        ]);
    }
}