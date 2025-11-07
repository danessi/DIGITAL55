<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriteCourseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating favorite courses...');
        
        $userIds = User::pluck('id')->toArray();
        $courseIds = Course::where('is_published', true)->pluck('id')->toArray();
        
        $favoritesCreated = 0;
        $totalFavorites = 30000;
        
        while ($favoritesCreated < $totalFavorites) {
            $favorites = [];
            $attempts = 0;
            
            while (count($favorites) < 1000 && $attempts < 2000) {
                $courseId = $courseIds[array_rand($courseIds)];
                $userId = $userIds[array_rand($userIds)];
                
                $key = $courseId . '-' . $userId;
                
                if (!isset($existing[$key])) {
                    $existing[$key] = true;
                    $favorites[] = [
                        'course_id' => $courseId,
                        'user_id' => $userId,
                        'favorited_at' => now(),
                    ];
                }
                
                $attempts++;
            }
            
            if (!empty($favorites)) {
                DB::table('course_user')->insert($favorites);
                $favoritesCreated += count($favorites);
                $this->command->info('Created ' . $favoritesCreated . ' favorites...');
            }
        }
        
        $this->command->info('Favorite courses created successfully!');
    }
}