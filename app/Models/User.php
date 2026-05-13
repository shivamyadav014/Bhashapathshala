<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
        'bio',
        'language_level',
        'goals',
        'total_points',
        'daily_streak',
        'last_activity_date',
        'performance_score',
        'level',
        'reset_token',
        'reset_token_created_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments');
    }

    public function quizResults()
    {
        return $this->hasMany(UserQuizResult::class);
    }

    public function exerciseSubmissions()
    {
        return $this->hasMany(ExerciseSubmission::class);
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    // Gamification relationships
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withTimestamps()
            ->withPivot('earned_at');
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an instructor.
     */
    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function performanceAnalytics()
    {
        return $this->hasMany(UserPerformanceAnalytic::class);
    }

    public function leaderboardEntries()
    {
        return $this->hasMany(Leaderboard::class);
    }

    // Methods
    // (Removed duplicate isInstructor and isAdmin methods)

    public function getCourseProgress($courseId)
    {
        $enrollment = $this->enrollments()->where('course_id', $courseId)->first();
        return $enrollment ? $enrollment->completion_percentage : 0;
    }
}
