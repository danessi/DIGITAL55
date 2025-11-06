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
        $instructors = $this->instructorService->getAllInstructorsOptimized();
        
        return response()->json([
            'success' => true,
            'data' => $instructors,
            'meta' => [
                'total' => $instructors->count(),
                'optimized' => true,
                'cached' => true,
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