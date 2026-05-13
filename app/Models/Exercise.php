<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'exercise_type',
        'content',
        'instructions',
        'hints',
        'difficulty_level',
        'points',
    ];

    protected $casts = [
        'hints' => 'array',
    ];

    // Relationships
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions()
    {
        return $this->hasMany(ExerciseSubmission::class);
    }

    // Methods
    public function getUserSubmission($userId)
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->latest()
            ->first();
    }

    public function getCompletionCount()
    {
        return $this->submissions()
            ->where('status', 'graded')
            ->distinct('user_id')
            ->count();
    }

    public function getAverageScore()
    {
        return $this->submissions()
            ->where('status', 'graded')
            ->whereNotNull('score')
            ->avg('score') ?? 0;
    }
}
