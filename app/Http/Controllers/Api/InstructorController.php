<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Services\InstructorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InstructorController extends Controller
{
    private InstructorService $instructorService;

    public function __construct(InstructorService $instructorService)
    {
        $this->instructorService = $instructorService;
    }

    public function index(): StreamedResponse
    {
        return response()->stream(function () {
            $this->instructorService->streamAllInstructors();
        }, 200, [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function paginated(Request $request): JsonResponse
    {
        $perPage = min((int) $request->input('per_page', 100), 1000);
        
        $paginator = Instructor::select('id', 'name', 'email', 'specialization')
            ->orderBy('id')
            ->cursorPaginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $paginator->items(),
            'pagination' => [
                'per_page' => $paginator->perPage(),
                'next_cursor' => $paginator->nextCursor()?->encode(),
                'prev_cursor' => $paginator->previousCursor()?->encode(),
                'has_more_pages' => $paginator->hasMorePages(),
            ],
            'links' => [
                'next' => $paginator->nextPageUrl(),
                'prev' => $paginator->previousPageUrl(),
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