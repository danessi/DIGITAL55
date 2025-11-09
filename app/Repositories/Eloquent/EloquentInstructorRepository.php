<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\InstructorRepositoryInterface;
use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentInstructorRepository implements InstructorRepositoryInterface
{
    public function getAllOptimized(): Collection
    {
        return collect();
    }

    public function streamOptimized(): Generator
    {
        $query = DB::table('instructors')
            ->select('id', 'name', 'email', 'specialization')
            ->orderBy('id');

        foreach ($query->cursor() as $row) {
            yield [
                'id' => $row->id,
                'name' => $row->name,
                'email' => $row->email,
                'specialization' => $row->specialization,
            ];
        }
    }

    public function count(): int
    {
        return DB::table('instructors')->count();
    }

    public function findById(int $id): ?array
    {
        $row = DB::table('instructors')
            ->select('id', 'name', 'email', 'bio', 'specialization')
            ->where('id', $id)
            ->first();

        return $row ? (array) $row : null;
    }

    public function exists(int $id): bool
    {
        return DB::table('instructors')->where('id', $id)->exists();
    }
}