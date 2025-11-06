<?php

namespace Tests\Feature\Api;

use App\Models\Instructor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstructorApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_can_list_instructors_optimized(): void
    {
        Instructor::factory()->count(100)->create();

        $response = $this->getJson('/api/v1/instructors');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta' => [
                    'total',
                    'optimized',
                    'cached',
                ]
            ]);

        $this->assertTrue($response->json('meta.optimized'));
    }

    public function test_can_show_instructor(): void
    {
        $instructor = Instructor::factory()->create();

        $response = $this->getJson("/api/v1/instructors/{$instructor->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'bio',
                    'specialization',
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $instructor->id,
                    'name' => $instructor->name,
                ]
            ]);
    }

    public function test_returns_404_for_non_existent_instructor(): void
    {
        $response = $this->getJson('/api/v1/instructors/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Instructor not found',
            ]);
    }
}