<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exercise_id',
        'submission_content',
        'score',
        'feedback',
        'status',
        'submitted_at',
        'graded_at',
    ];

    protected $casts = [
        'score' => 'float',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    // Methods
    public function gradeSubmission($score, $feedback = '')
    {
        $this->update([
            'score' => $score,
            'feedback' => $feedback,
            'status' => 'graded',
            'graded_at' => now(),
        ]);
    }

    public function getPercentageScore()
    {
        if ($this->score === null) {
            return null;
        }
        return round(($this->score / $this->exercise->points) * 100, 2);
    }

    public function isPassed()
    {
        return $this->score !== null && $this->getPercentageScore() >= 70;
    }
}
