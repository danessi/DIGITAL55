<?php

namespace Database\Seeders;

use App\Models\Instructor;
use Illuminate\Database\Seeder;

class InstructorSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating instructors...');
        
        Instructor::factory()->count(50000)->create();
        
        $this->command->info('50,000 instructors created successfully!');
    }
}