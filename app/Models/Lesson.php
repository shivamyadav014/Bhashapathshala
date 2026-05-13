<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'content',
        'notes',
        'order',
        'duration_minutes',
        'video_url',
        'cover_image',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    // Methods
    public function isCompletedBy($userId)
    {
        return $this->lessonProgress()
            ->where('user_id', $userId)
            ->where('is_completed', true)
            ->exists();
    }

    public function getProgressFor($userId)
    {
        return $this->lessonProgress()
            ->where('user_id', $userId)
            ->first();
    }
}
