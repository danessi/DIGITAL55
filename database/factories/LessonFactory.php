<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(2),
            'video_url' => fake()->url() . '/video.mp4',
            'duration_minutes' => fake()->numberBetween(5, 60),
            'order' => fake()->numberBetween(1, 50),
            'is_preview' => fake()->boolean(20),
        ];
    }
}