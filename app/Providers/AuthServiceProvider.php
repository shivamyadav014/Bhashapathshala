<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Policies\CoursePolicy;
use App\Policies\ExercisePolicy;
use App\Policies\LessonPolicy;
use App\Policies\QuizPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
        Exercise::class => ExercisePolicy::class,
        Lesson::class => LessonPolicy::class,
        Quiz::class => QuizPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('instructor', function ($user) {
            return $user->role === 'instructor';
        });

        Gate::define('student', function ($user) {
            return $user->role === 'student';
        });
    }
}

