<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating lessons...');
        
        Course::chunk(500, function ($courses) {
            foreach ($courses as $course) {
                $lessonCount = rand(5, 15);
                
                for ($i = 1; $i <= $lessonCount; $i++) {
                    Lesson::create([
                        'course_id' => $course->id,
                        'title' => 'Lesson ' . $i . ': ' . fake()->sentence(3),
                        'description' => fake()->paragraph(2),
                        'video_url' => fake()->url() . '/video' . $i . '.mp4',
                        'duration_minutes' => fake()->numberBetween(5, 60),
                        'order' => $i,
                        'is_preview' => $i <= 2,
                    ]);
                }
            }
            
            $this->command->info('Processing lessons batch...');
        });
        
        $this->command->info('Lessons created successfully!');
    }
}