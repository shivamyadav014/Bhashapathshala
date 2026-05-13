<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'passing_score',
        'total_questions',
        'time_limit_minutes',
        'show_results_immediately',
        'is_published',
    ];

    protected $casts = [
        'show_results_immediately' => 'boolean',
        'is_published' => 'boolean',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function results()
    {
        return $this->hasMany(UserQuizResult::class);
    }

    // Methods
    public function getUserResult($userId)
    {
        return $this->results()
            ->where('user_id', $userId)
            ->latest()
            ->first();
    }

    public function hasUserCompleted($userId)
    {
        return $this->results()
            ->where('user_id', $userId)
            ->exists();
    }

    public function getAverageScore()
    {
        return $this->results()
            ->avg('score') ?? 0;
    }

    public function getPassRate()
    {
        $total = $this->results()->count();
        if ($total === 0) {
            return 0;
        }
        $passed = $this->results()->where('passed', true)->count();
        return round(($passed / $total) * 100, 2);
    }

    public function getTotalAttempts()
    {
        return $this->results()->count();
    }

    /**
     * Grade a quiz attempt and persist the result.
     *
     * @param  array<string, mixed>  $answers  question id => answer
     * @return array{result: UserQuizResult, feedback: ?string}
     */
    public function evaluateAttempt(User $user, array $answers): array
    {
        $this->loadMissing('questions');

        $correctQuestionCount = 0;
        $totalScore = 0;
        $maxScore = 0;

        foreach ($this->questions as $question) {
            $maxScore += $question->points;

            if (isset($answers[$question->id])) {
                if ($question->isCorrect($answers[$question->id])) {
                    $correctQuestionCount++;
                    $totalScore += $question->points;
                }
            }
        }

        $scorePercent = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
        $passed = $scorePercent >= (float) $this->passing_score;

        $result = UserQuizResult::create([
            'user_id' => $user->id,
            'quiz_id' => $this->id,
            'score' => $scorePercent,
            'total_questions' => $this->questions->count(),
            'correct_answers' => $correctQuestionCount,
            'passed' => $passed,
            'completed_at' => now(),
        ]);

        $feedback = $this->show_results_immediately
            ? $this->buildResultFeedback($result)
            : null;

        return ['result' => $result, 'feedback' => $feedback];
    }

    public function buildResultFeedback(UserQuizResult $result): string
    {
        $feedback = "Quiz completed!\n\n";
        $feedback .= 'Score: '.round($result->score, 2)."%\n";
        $feedback .= 'Correct answers (by points): '.$result->correct_answers.'/'.$result->total_questions."\n";
        $feedback .= 'Status: '.($result->passed ? 'PASSED ✓' : 'FAILED ✗')."\n";
        $feedback .= 'Grade: '.$result->getGrade()."\n";

        return $feedback;
    }
}
