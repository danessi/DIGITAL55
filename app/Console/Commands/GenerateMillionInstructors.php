<?php

namespace App\Console\Commands;

use App\Models\Instructor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateMillionInstructors extends Command
{
    protected $signature = 'instructors:generate-million';
    
    protected $description = 'Generate 1 million instructors for performance testing';

    public function handle(): int
    {
        $this->warn('⚠️  This will generate 1,000,000 instructors. This may take 30-60 minutes.');
        
        if (!$this->confirm('Do you want to continue?')) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }
        
        $this->info('Starting generation...');
        
        $total = 1000000;
        $chunkSize = 5000;
        $chunks = $total / $chunkSize;
        
        $bar = $this->output->createProgressBar($chunks);
        $bar->start();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //Instructor::truncate(); // Limpia antes (opcional, pero recomendado)
        
        for ($i = 0; $i < $chunks; $i++) {
            $instructors = [];
            $startId = $i * $chunkSize + 1;
            
            for ($j = 0; $j < $chunkSize; $j++) {
                $id = $startId + $j;
                $instructors[] = [
                    'name' => fake()->name(),
                    //'email' => fake()->unique()->safeEmail(),
                    'email' => "instructor_{$id}@test.local",
                    'bio' => fake()->paragraph(3),
                    'specialization' => fake()->randomElement([
                        'Web Development',
                        'Mobile Development',
                        'Data Science',
                        'Machine Learning',
                        'DevOps',
                        'Cloud Computing',
                        'Cybersecurity',
                        'UI/UX Design',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            Instructor::insert($instructors);
            $bar->advance();
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $bar->finish();
        $this->newLine(2);
        $this->info('✅ 1,000,000 instructors generated successfully!');
        
        return Command::SUCCESS;
    }
}