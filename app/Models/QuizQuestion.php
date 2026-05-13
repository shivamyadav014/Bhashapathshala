<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'question_type',
        'options',
        'correct_answer',
        'explanation',
        'points',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Methods
    public function isCorrect($userAnswer)
    {
        $given = trim((string) $userAnswer);
        $expected = trim((string) $this->correct_answer);

        if (in_array($this->question_type, ['short_answer', 'essay'], true)) {
            return strcasecmp($given, $expected) === 0;
        }

        return $given === $expected;
    }
}
