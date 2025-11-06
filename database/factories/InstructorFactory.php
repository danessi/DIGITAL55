<?php

namespace Database\Factories;

use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructorFactory extends Factory
{
    protected $model = Instructor::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
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
        ];
    }
}