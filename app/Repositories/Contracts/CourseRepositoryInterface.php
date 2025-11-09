<?php

namespace App\Repositories\Contracts;

use App\Entities\CourseEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CourseRepositoryInterface
{
    public function findById(int $id): ?CourseEntity;
    
    public function getAll(int $perPage = 15): LengthAwarePaginator;
    
    public function create(CourseEntity $course): CourseEntity;
    
    public function update(int $id, CourseEntity $course): CourseEntity;
    
    public function delete(int $id): bool;
    
    public function findByInstructor(int $instructorId, int $perPage = 15): LengthAwarePaginator;
    
    public function getPublished(int $perPage = 15): LengthAwarePaginator;
}