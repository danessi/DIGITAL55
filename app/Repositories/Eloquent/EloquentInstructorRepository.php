<?php

namespace App\Repositories\Eloquent;

use App\Models\Instructor;
use App\Repositories\Contracts\InstructorRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class EloquentInstructorRepository implements InstructorRepositoryInterface
{
    public function getAllOptimized(): Collection
    {
        return $this->getAllWithCursorPagination();
    }

    private function getAllWithCursorPagination(): Collection
    {
        $instructors = collect();
        
        Instructor::select('id', 'name', 'email', 'specialization')
            ->orderBy('id')
            ->lazy(1000)
            ->each(function ($instructor) use (&$instructors) {
                $instructors->push([
                    'id' => $instructor->id,
                    'name' => $instructor->name,
                    'email' => $instructor->email,
                    'specialization' => $instructor->specialization,
                ]);
            });
        
        return $instructors;
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