<?php

namespace App\Repositories\Eloquent;

use App\Entities\CourseEntity;
use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentCourseRepository implements CourseRepositoryInterface
{
    public function findById(int $id): ?CourseEntity
    {
        $course = Course::find($id);
        
        if (!$course) {
            return null;
        }
        
        return $this->mapToEntity($course);
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Course::with('instructor:id,name,email')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function create(CourseEntity $course): CourseEntity
    {
        $model = Course::create([
            'instructor_id' => $course->getInstructorId(),
            'title' => $course->getTitle(),
            'description' => $course->getDescription(),
            'price' => $course->getPrice(),
            'level' => $course->getLevel(),
            'duration_hours' => $course->getDurationHours(),
            'is_published' => $course->isPublished(),
        ]);

        return $this->mapToEntity($model);
    }

    public function update(int $id, CourseEntity $course): CourseEntity
    {
        $model = Course::findOrFail($id);
        
        $model->update([
            'instructor_id' => $course->getInstructorId(),
            'title' => $course->getTitle(),
            'description' => $course->getDescription(),
            'price' => $course->getPrice(),
            'level' => $course->getLevel(),
            'duration_hours' => $course->getDurationHours(),
            'is_published' => $course->isPublished(),
        ]);

        return $this->mapToEntity($model->fresh());
    }

    public function delete(int $id): bool
    {
        $course = Course::find($id);
        
        if (!$course) {
            return false;
        }
        
        return $course->delete();
    }

    public function findByInstructor(int $instructorId, int $perPage = 15): LengthAwarePaginator
    {
        return Course::where('instructor_id', $instructorId)
            ->with('instructor:id,name,email')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getPublished(int $perPage = 15): LengthAwarePaginator
    {
        return Course::where('is_published', true)
            ->with('instructor:id,name,email')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    private function mapToEntity(Course $model): CourseEntity
    {
        return new CourseEntity(
            instructorId: $model->instructor_id,
            title: $model->title,
            description: $model->description,
            price: $model->price,
            level: $model->level,
            durationHours: $model->duration_hours,
            isPublished: $model->is_published,
            id: $model->id
        );
    }
}