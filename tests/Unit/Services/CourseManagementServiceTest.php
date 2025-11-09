<?php

namespace Tests\Unit\Services;

use App\Entities\CourseEntity;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\InstructorRepositoryInterface;
use App\Services\CourseManagementService;
use InvalidArgumentException;
use Mockery;
use Tests\TestCase;

class CourseManagementServiceTest extends TestCase
{
    private $courseRepository;
    private $instructorRepository;
    private CourseManagementService $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->courseRepository = Mockery::mock(CourseRepositoryInterface::class);
        $this->instructorRepository = Mockery::mock(InstructorRepositoryInterface::class);
        $this->service = new CourseManagementService(
            $this->courseRepository,
            $this->instructorRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_creates_course_successfully(): void
    {
        $data = [
            'instructor_id' => 1,
            'title' => 'Laravel Course',
            'description' => 'Learn Laravel framework',
            'price' => 99.99,
            'level' => 'intermediate',
            'duration_hours' => 40,
            'is_published' => true,
        ];

        $this->instructorRepository
            ->shouldReceive('exists')
            ->once()
            ->with(1)
            ->andReturn(true);

        $expectedEntity = new CourseEntity(
            instructorId: 1,
            title: 'Laravel Course',
            description: 'Learn Laravel framework',
            price: 99.99,
            level: 'intermediate',
            durationHours: 40,
            isPublished: true,
            id: 1
        );

        $this->courseRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn($expectedEntity);

        $result = $this->service->createCourse($data);

        $this->assertInstanceOf(CourseEntity::class, $result);
        $this->assertEquals('Laravel Course', $result->getTitle());
    }

    public function test_throws_exception_when_instructor_not_found(): void
    {
        $data = [
            'instructor_id' => 999,
            'title' => 'Laravel Course',
            'description' => 'Learn Laravel framework',
            'price' => 99.99,
            'level' => 'intermediate',
            'duration_hours' => 40,
        ];

        $this->instructorRepository
            ->shouldReceive('exists')
            ->once()
            ->with(999)
            ->andReturn(false);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Instructor not found');

        $this->service->createCourse($data);
    }

    public function test_updates_course_successfully(): void
    {
        $courseId = 1;
        $data = [
            'title' => 'Updated Title',
            'price' => 149.99,
        ];

        $existingCourse = new CourseEntity(
            instructorId: 1,
            title: 'Old Title',
            description: 'Old description',
            price: 99.99,
            level: 'beginner',
            durationHours: 20,
            isPublished: false,
            id: 1
        );

        $this->courseRepository
            ->shouldReceive('findById')
            ->once()
            ->with($courseId)
            ->andReturn($existingCourse);

        $updatedEntity = new CourseEntity(
            instructorId: 1,
            title: 'Updated Title',
            description: 'Old description',
            price: 149.99,
            level: 'beginner',
            durationHours: 20,
            isPublished: false,
            id: 1
        );

        $this->courseRepository
            ->shouldReceive('update')
            ->once()
            ->andReturn($updatedEntity);

        $result = $this->service->updateCourse($courseId, $data);

        $this->assertEquals('Updated Title', $result->getTitle());
        $this->assertEquals(149.99, $result->getPrice());
    }

    public function test_throws_exception_when_updating_non_existent_course(): void
    {
        $this->courseRepository
            ->shouldReceive('findById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Course not found');

        $this->service->updateCourse(999, ['title' => 'New Title']);
    }

    public function test_deletes_course_successfully(): void
    {
        $courseId = 1;

        $existingCourse = new CourseEntity(
            instructorId: 1,
            title: 'Course to Delete',
            description: 'Description',
            price: 99.99,
            level: 'beginner',
            durationHours: 20,
            id: 1
        );

        $this->courseRepository
            ->shouldReceive('findById')
            ->once()
            ->with($courseId)
            ->andReturn($existingCourse);

        $this->courseRepository
            ->shouldReceive('delete')
            ->once()
            ->with($courseId)
            ->andReturn(true);

        $result = $this->service->deleteCourse($courseId);

        $this->assertTrue($result);
    }

    public function test_throws_exception_when_deleting_non_existent_course(): void
    {
        $this->courseRepository
            ->shouldReceive('findById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Course not found');

        $this->service->deleteCourse(999);
    }

    public function test_gets_course_by_id(): void
    {
        $courseId = 1;

        $expectedCourse = new CourseEntity(
            instructorId: 1,
            title: 'Test Course',
            description: 'Test Description',
            price: 99.99,
            level: 'beginner',
            durationHours: 20,
            id: 1
        );

        $this->courseRepository
            ->shouldReceive('findById')
            ->once()
            ->with($courseId)
            ->andReturn($expectedCourse);

        $result = $this->service->getCourse($courseId);

        $this->assertInstanceOf(CourseEntity::class, $result);
        $this->assertEquals('Test Course', $result->getTitle());
    }
}