<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating courses...');
        
        $instructorIds = Instructor::pluck('id')->toArray();
        
        $chunkSize = 1000;
        $totalCourses = 2000;
        
        for ($i = 0; $i < $totalCourses / $chunkSize; $i++) {
            $courses = [];
            
            for ($j = 0; $j < $chunkSize; $j++) {
                $courses[] = [
                    'instructor_id' => $instructorIds[array_rand($instructorIds)],
                    'title' => fake()->sentence(4),
                    'description' => fake()->paragraph(5),
                    'price' => fake()->randomFloat(2, 9.99, 299.99),
                    'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
                    'duration_hours' => fake()->numberBetween(5, 120),
                    'is_published' => fake()->boolean(80),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            Course::insert($courses);
            $this->command->info('Created ' . (($i + 1) * $chunkSize) . ' courses...');
        }
        
        $this->command->info('2,000 courses created successfully!');
    }
}