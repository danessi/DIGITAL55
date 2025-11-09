<?php

namespace App\Services;

use App\Entities\CourseEntity;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\InstructorRepositoryInterface;
use InvalidArgumentException;

class CourseManagementService
{
    private CourseRepositoryInterface $courseRepository;
    private InstructorRepositoryInterface $instructorRepository;

    public function __construct(
        CourseRepositoryInterface $courseRepository,
        InstructorRepositoryInterface $instructorRepository
    ) {
        $this->courseRepository = $courseRepository;
        $this->instructorRepository = $instructorRepository;
    }

    public function createCourse(array $data): CourseEntity
    {
        if (!$this->instructorRepository->exists($data['instructor_id'])) {
            throw new InvalidArgumentException('Instructor not found');
        }

        $entity = new CourseEntity(
            instructorId: $data['instructor_id'],
            title: $data['title'],
            description: $data['description'],
            price: $data['price'],
            level: $data['level'],
            durationHours: $data['duration_hours'],
            isPublished: $data['is_published'] ?? false
        );

        return $this->courseRepository->create($entity);
    }

    public function updateCourse(int $id, array $data): CourseEntity
    {
        $existing = $this->courseRepository->findById($id);
        
        if (!$existing) {
            throw new InvalidArgumentException('Course not found');
        }

        if (isset($data['instructor_id']) && !$this->instructorRepository->exists($data['instructor_id'])) {
            throw new InvalidArgumentException('Instructor not found');
        }

        $entity = new CourseEntity(
            instructorId: $data['instructor_id'] ?? $existing->getInstructorId(),
            title: $data['title'] ?? $existing->getTitle(),
            description: $data['description'] ?? $existing->getDescription(),
            price: $data['price'] ?? $existing->getPrice(),
            level: $data['level'] ?? $existing->getLevel(),
            durationHours: $data['duration_hours'] ?? $existing->getDurationHours(),
            isPublished: $data['is_published'] ?? $existing->isPublished(),
            id: $id
        );

        return $this->courseRepository->update($id, $entity);
    }

    public function deleteCourse(int $id): bool
    {
        $existing = $this->courseRepository->findById($id);
        
        if (!$existing) {
            throw new InvalidArgumentException('Course not found');
        }

        return $this->courseRepository->delete($id);
    }

    public function getCourse(int $id): ?CourseEntity
    {
        return $this->courseRepository->findById($id);
    }
}