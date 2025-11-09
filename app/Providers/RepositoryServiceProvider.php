<?php

namespace App\Providers;

use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\InstructorRepositoryInterface;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\Eloquent\EloquentCourseRepository;
use App\Repositories\Eloquent\EloquentInstructorRepository;
use App\Repositories\Eloquent\EloquentReviewRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CourseRepositoryInterface::class, EloquentCourseRepository::class);
        
        $this->app->bind(InstructorRepositoryInterface::class, EloquentInstructorRepository::class);
        
        $this->app->bind(ReviewRepositoryInterface::class, EloquentReviewRepository::class);
    }

    public function boot(): void
    {
        //
    }
}