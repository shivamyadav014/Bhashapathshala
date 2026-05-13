<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'language',
        'level',
        'instructor_id',
        'thumbnail',
        'duration_hours',
        'total_lessons',
        'rating',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'rating' => 'float',
    ];

    // Relationships
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'course_enrollments');
    }

    // Methods
    public function getTotalEnrollments()
    {
        return $this->enrollments()->count();
    }

    public function getCompletionRate()
    {
        $total = $this->getTotalEnrollments();
        if ($total === 0) {
            return 0;
        }
        $completed = $this->enrollments()->where('status', 'completed')->count();
        return round(($completed / $total) * 100, 2);
    }

    public function getStudentProgress($userId)
    {
        return $this->enrollments()
            ->where('user_id', $userId)
            ->first();
    }
}
