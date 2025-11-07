<?php

namespace App\Services;

use App\Repositories\Contracts\InstructorRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class InstructorService
{
    private InstructorRepositoryInterface $instructorRepository;
    
    private const CACHE_KEY = 'instructors:all:optimized';
    private const CACHE_TTL = 3600;

    public function __construct(InstructorRepositoryInterface $instructorRepository)
    {
        $this->instructorRepository = $instructorRepository;
    }

    public function getAllInstructorsOptimized(): Collection
    {
        $startTime = microtime(true);
        
        $instructors = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            Log::info('Cache MISS: Fetching instructors from database');
            return $this->instructorRepository->getAllOptimized();
        });
        
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::info('Instructors retrieved', [
            'count' => $instructors->count(),
            'execution_time_ms' => $executionTime,
            'cached' => Cache::has(self::CACHE_KEY),
        ]);
        
        return $instructors;
    }

    public function getInstructor(int $id): ?array
    {
        return $this->instructorRepository->findById($id);
    }
    
    public function clearCache(): bool
    {
        return Cache::forget(self::CACHE_KEY);
    }
}