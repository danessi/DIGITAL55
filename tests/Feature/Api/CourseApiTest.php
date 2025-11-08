<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseApiTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_can_list_courses(): void
    {
        $instructor = Instructor::factory()->create();
        Course::factory()->count(5)->create(['instructor_id' => $instructor->id]);

        $response = $this->getJson('/api/v1/courses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'price',
                        'level',
                        'duration_hours',
                        'is_published',
                    ]
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ]
            ]);
    }

    public function test_can_create_course(): void
    {
        $instructor = Instructor::factory()->create();

        $data = [
            'instructor_id' => $instructor->id,
            'title' => 'New Laravel Course',
            'description' => 'Learn Laravel from scratch with this comprehensive guide',
            'price' => 99.99,
            'level' => 'beginner',
            'duration_hours' => 40,
            'is_published' => true,
        ];

        $response = $this->postJson('/api/v1/courses', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'instructor_id',
                    'title',
                    'description',
                    'price',
                    'level',
                    'duration_hours',
                    'is_published',
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'New Laravel Course',
                    'price' => 99.99,
                ]
            ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'New Laravel Course',
            'price' => 99.99,
        ]);
    }

    public function test_cannot_create_course_with_invalid_data(): void
    {
        $response = $this->postJson('/api/v1/courses', [
            'instructor_id' => 999,
            'title' => 'AB',
            'description' => 'Short',
            'price' => -10,
            'level' => 'invalid',
            'duration_hours' => -5,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'instructor_id',
                'title',
                'description',
                'price',
                'level',
                'duration_hours',
            ]);
    }

    public function test_can_show_course(): void
    {
        $instructor = Instructor::factory()->create();
        $course = Course::factory()->create(['instructor_id' => $instructor->id]);

        $response = $this->getJson("/api/v1/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'instructor_id',
                    'title',
                    'description',
                    'price',
                    'level',
                    'duration_hours',
                    'is_published',
                    'rating' => [
                        'average_rating',
                        'total_reviews',
                        'rating_display',
                    ]
                ]
            ]);
    }

    public function test_returns_404_for_non_existent_course(): void
    {
        $response = $this->getJson('/api/v1/courses/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Course not found',
            ]);
    }

    public function test_can_update_course(): void
    {
        $instructor = Instructor::factory()->create();
        $course = Course::factory()->create(['instructor_id' => $instructor->id]);

        $updateData = [
            'title' => 'Updated Course Title',
            'price' => 149.99,
        ];

        $response = $this->putJson("/api/v1/courses/{$course->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Updated Course Title',
                    'price' => 149.99,
                ]
            ]);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Updated Course Title',
            'price' => 149.99,
        ]);
    }

    public function test_can_delete_course(): void
    {
        $instructor = Instructor::factory()->create();
        $course = Course::factory()->create(['instructor_id' => $instructor->id]);

        $response = $this->deleteJson("/api/v1/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Course deleted successfully',
            ]);

        $this->assertSoftDeleted('courses', ['id' => $course->id]);
    }

    public function test_can_get_published_courses(): void
    {
        $instructor = Instructor::factory()->create();
        Course::factory()->count(3)->published()->create(['instructor_id' => $instructor->id]);
        Course::factory()->count(2)->unpublished()->create(['instructor_id' => $instructor->id]);

        $response = $this->getJson('/api/v1/courses/published');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertCount(3, $data);
        
        foreach ($data as $course) {
            $this->assertTrue($course['is_published']);
        }
    }

    public function test_can_get_course_rating(): void
    {
        $instructor = Instructor::factory()->create();
        $course = Course::factory()->create(['instructor_id' => $instructor->id]);

        Review::factory()->count(5)->create([
            'course_id' => $course->id,
            'user_id' => User::factory(),
            'rating' => 4,
        ]);

        $response = $this->getJson("/api/v1/courses/{$course->id}/rating");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'average_rating',
                    'total_reviews',
                    'rating_display',
                ]
            ]);

        $this->assertEquals(4.0, $response->json('data.average_rating'));
        $this->assertEquals(5, $response->json('data.total_reviews'));
    }
}