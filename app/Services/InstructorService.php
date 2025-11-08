<?php

namespace App\Services;

use App\Repositories\Contracts\InstructorRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class InstructorService
{
    private InstructorRepositoryInterface $instructorRepository;
    
    private const CACHE_KEY_COUNT = 'instructors:count';
    private const CACHE_TTL = 3600;
    private const PROGRESS_INTERVAL = 10000;

    public function __construct(InstructorRepositoryInterface $instructorRepository)
    {
        $this->instructorRepository = $instructorRepository;
    }

    public function streamAllInstructors(): void
    {
        set_time_limit(0);
        ignore_user_abort(true);

        $total = Cache::remember(self::CACHE_KEY_COUNT, self::CACHE_TTL, fn() => 
            $this->instructorRepository->count()
        );

        echo '{"success":true,"data":[';
        
        $first = true;
        $processed = 0;

        foreach ($this->instructorRepository->streamOptimized() as $instructor) {
            echo ($first ? '' : ',') . json_encode($instructor, JSON_UNESCAPED_UNICODE);
            $first = false;
            $processed++;

            if ($processed % self::PROGRESS_INTERVAL === 0) {
                Log::info('Streaming progress', [
                    'processed' => $processed,
                    'total' => $total,
                    'percentage' => round(($processed / max(1, $total)) * 100, 2)
                ]);
                
                if (ob_get_level() > 0) {
                    @ob_flush();
                }
                flush();
            }
        }

        echo '],"meta":{"total":' . $total . ',"processed":' . $processed . '}}';
        
        if (ob_get_level() > 0) {
            @ob_flush();
        }
        flush();
    }

    public function getInstructor(int $id): ?array
    {
        return $this->instructorRepository->findById($id);
    }
}