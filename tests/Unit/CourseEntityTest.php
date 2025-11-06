<?php

namespace Tests\Unit\Entities;

use App\Entities\CourseEntity;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CourseEntityTest extends TestCase
{
    public function test_can_create_valid_course_entity(): void
    {
        $course = new CourseEntity(
            instructorId: 1,
            title: 'Laravel Advanced',
            description: 'Learn advanced Laravel techniques and best practices',
            price: 99.99,
            level: 'advanced',
            durationHours: 40,
            isPublished: true,
            id: 1
        );

        $this->assertEquals(1, $course->getId());
        $this->assertEquals(1, $course->getInstructorId());
        $this->assertEquals('Laravel Advanced', $course->getTitle());
        $this->assertEquals(99.99, $course->getPrice());
        $this->assertEquals('advanced', $course->getLevel());
        $this->assertEquals(40, $course->getDurationHours());
        $this->assertTrue($course->isPublished());
    }

    public function test_throws_exception_for_empty_title(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title cannot be empty');

        new CourseEntity(
            instructorId: 1,
            title: '',
            description: 'Valid description',
            price: 99.99,
            level: 'beginner',
            durationHours: 10
        );
    }

    public function test_throws_exception_for_short_title(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title must be at least 3 characters');

        new CourseEntity(
            instructorId: 1,
            title: 'AB',
            description: 'Valid description',
            price: 99.99,
            level: 'beginner',
            durationHours: 10
        );
    }

    public function test_throws_exception_for_long_title(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title cannot exceed 255 characters');

        new CourseEntity(
            instructorId: 1,
            title: str_repeat('A', 256),
            description: 'Valid description',
            price: 99.99,
            level: 'beginner',
            durationHours: 10
        );
    }

    public function test_throws_exception_for_short_description(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Description must be at least 10 characters');

        new CourseEntity(
            instructorId: 1,
            title: 'Valid Title',
            description: 'Short',
            price: 99.99,
            level: 'beginner',
            durationHours: 10
        );
    }

    public function test_throws_exception_for_negative_price(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Price cannot be negative');

        new CourseEntity(
            instructorId: 1,
            title: 'Valid Title',
            description: 'Valid description here',
            price: -10.00,
            level: 'beginner',
            durationHours: 10
        );
    }

    public function test_throws_exception_for_excessive_price(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Price cannot exceed 9999.99');

        new CourseEntity(
            instructorId: 1,
            title: 'Valid Title',
            description: 'Valid description here',
            price: 10000.00,
            level: 'beginner',
            durationHours: 10
        );
    }

    public function test_throws_exception_for_invalid_level(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid level');

        new CourseEntity(
            instructorId: 1,
            title: 'Valid Title',
            description: 'Valid description here',
            price: 99.99,
            level: 'expert',
            durationHours: 10
        );
    }

    public function test_throws_exception_for_negative_duration(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Duration cannot be negative');

        new CourseEntity(
            instructorId: 1,
            title: 'Valid Title',
            description: 'Valid description here',
            price: 99.99,
            level: 'beginner',
            durationHours: -5
        );
    }

    public function test_throws_exception_for_excessive_duration(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Duration cannot exceed 500 hours');

        new CourseEntity(
            instructorId: 1,
            title: 'Valid Title',
            description: 'Valid description here',
            price: 99.99,
            level: 'beginner',
            durationHours: 501
        );
    }

    public function test_can_publish_course(): void
    {
        $course = new CourseEntity(
            instructorId: 1,
            title: 'Test Course',
            description: 'Test description',
            price: 49.99,
            level: 'beginner',
            durationHours: 20,
            isPublished: false
        );

        $this->assertFalse($course->isPublished());
        
        $course->publish();
        
        $this->assertTrue($course->isPublished());
    }

    public function test_can_unpublish_course(): void
    {
        $course = new CourseEntity(
            instructorId: 1,
            title: 'Test Course',
            description: 'Test description',
            price: 49.99,
            level: 'beginner',
            durationHours: 20,
            isPublished: true
        );

        $this->assertTrue($course->isPublished());
        
        $course->unpublish();
        
        $this->assertFalse($course->isPublished());
    }

    public function test_can_check_if_free(): void
    {
        $freeCourse = new CourseEntity(
            instructorId: 1,
            title: 'Free Course',
            description: 'Free course description',
            price: 0.0,
            level: 'beginner',
            durationHours: 10
        );

        $paidCourse = new CourseEntity(
            instructorId: 1,
            title: 'Paid Course',
            description: 'Paid course description',
            price: 99.99,
            level: 'beginner',
            durationHours: 10
        );

        $this->assertTrue($freeCourse->isFree());
        $this->assertFalse($paidCourse->isFree());
    }

    public function test_can_check_if_for_beginners(): void
    {
        $beginnerCourse = new CourseEntity(
            instructorId: 1,
            title: 'Beginner Course',
            description: 'Course for beginners',
            price: 49.99,
            level: 'beginner',
            durationHours: 10
        );

        $advancedCourse = new CourseEntity(
            instructorId: 1,
            title: 'Advanced Course',
            description: 'Course for advanced users',
            price: 149.99,
            level: 'advanced',
            durationHours: 40
        );

        $this->assertTrue($beginnerCourse->isForBeginners());
        $this->assertFalse($advancedCourse->isForBeginners());
    }

    public function test_can_convert_to_array(): void
    {
        $course = new CourseEntity(
            instructorId: 1,
            title: 'Test Course',
            description: 'Test description',
            price: 99.99,
            level: 'intermediate',
            durationHours: 30,
            isPublished: true,
            id: 5
        );

        $array = $course->toArray();

        $this->assertIsArray($array);
        $this->assertEquals(5, $array['id']);
        $this->assertEquals(1, $array['instructor_id']);
        $this->assertEquals('Test Course', $array['title']);
        $this->assertEquals('Test description', $array['description']);
        $this->assertEquals(99.99, $array['price']);
        $this->assertEquals('intermediate', $array['level']);
        $this->assertEquals(30, $array['duration_hours']);
        $this->assertTrue($array['is_published']);
    }

    public function test_price_is_rounded_to_two_decimals(): void
    {
        $course = new CourseEntity(
            instructorId: 1,
            title: 'Test Course',
            description: 'Test description',
            price: 99.999,
            level: 'beginner',
            durationHours: 10
        );

        $this->assertEquals(100.00, $course->getPrice());
    }
}