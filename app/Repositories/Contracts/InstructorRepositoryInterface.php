<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface InstructorRepositoryInterface
{
    public function getAllOptimized(): Collection;
    
    public function findById(int $id): ?array;
    
    public function exists(int $id): bool;
}