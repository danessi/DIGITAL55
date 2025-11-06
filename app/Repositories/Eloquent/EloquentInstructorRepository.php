<?php

namespace App\Repositories\Eloquent;

use App\Models\Instructor;
use App\Repositories\Contracts\InstructorRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentInstructorRepository implements InstructorRepositoryInterface
{
    public function getAllOptimized(): Collection
    {
        return Instructor::select('id', 'name', 'email', 'specialization')
            ->orderBy('name')
            ->chunk(1000, function ($instructors) {
                return $instructors;
            });
    }

    public function findById(int $id): ?array
    {
        $instructor = Instructor::select('id', 'name', 'email', 'bio', 'specialization')
            ->find($id);
        
        if (!$instructor) {
            return null;
        }
        
        return $instructor->toArray();
    }

    public function exists(int $id): bool
    {
        return Instructor::where('id', $id)->exists();
    }
}