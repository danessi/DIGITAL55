<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            InstructorSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            LessonSeeder::class,
            ReviewSeeder::class,
            FavoriteCourseSeeder::class,
        ]);
    }
}