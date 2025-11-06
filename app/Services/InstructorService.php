<?php

namespace App\Services;

use App\Repositories\Contracts\InstructorRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class InstructorService
{
    private InstructorRepositoryInterface $instructorRepository;

    public function __construct(InstructorRepositoryInterface $instructorRepository)
    {
        $this->instructorRepository = $instructorRepository;
    }

    public function getAllInstructorsOptimized(): Collection
    {
        return Cache::remember('all_instructors_optimized', 3600, function () {
            return $this->instructorRepository->getAllOptimized();
        });
    }

    public function getInstructor(int $id): ?array
    {
        return $this->instructorRepository->findById($id);
    }
}