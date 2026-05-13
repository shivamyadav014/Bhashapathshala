<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPerformanceAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'lessons_completed',
        'exercises_submitted',
        'quizzes_attempted',
        'average_quiz_score',
        'average_exercise_score',
        'points_earned',
        'topics_practiced',
        'weak_areas',
    ];

    protected $casts = [
        'date' => 'date',
        'topics_practiced' => 'json',
        'weak_areas' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
