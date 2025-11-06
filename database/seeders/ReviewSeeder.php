<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating reviews...');
        
        $userIds = User::pluck('id')->toArray();
        $courseIds = Course::where('is_published', true)->pluck('id')->toArray();
        
        $reviewsCreated = 0;
        $totalReviews = 500000;
        
        while ($reviewsCreated < $totalReviews) {
            $reviews = [];
            $attempts = 0;
            
            while (count($reviews) < 1000 && $attempts < 2000) {
                $courseId = $courseIds[array_rand($courseIds)];
                $userId = $userIds[array_rand($userIds)];
                
                $key = $courseId . '-' . $userId;
                
                if (!isset($existing[$key])) {
                    $existing[$key] = true;
                    $reviews[] = [
                        'course_id' => $courseId,
                        'user_id' => $userId,
                        'rating' => rand(1, 5),
                        'comment' => fake()->boolean(70) ? fake()->paragraph(2) : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                $attempts++;
            }
            
            if (!empty($reviews)) {
                Review::insert($reviews);
                $reviewsCreated += count($reviews);
                $this->command->info('Created ' . $reviewsCreated . ' reviews...');
            }
        }
        
        $this->command->info('Reviews created successfully!');
    }
}