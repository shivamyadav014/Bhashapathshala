<?php

namespace App\Services;

use App\Models\ExerciseSubmission;
use App\Models\UserQuizResult;

class FeedbackService
{
    /**
     * Generate feedback for quiz result
     */
    public function generateQuizFeedback(UserQuizResult $result): array
    {
        $totalQuestions = $result->total_questions;
        $correctAnswers = $result->correct_answers;
        $accuracy = $result->accuracy_percentage;
        
        $feedback = [
            'summary' => $this->getQuizSummary($accuracy, $correctAnswers, $totalQuestions),
            'grade' => $result->grade_letter,
            'score' => $result->score,
            'accuracy_percentage' => $accuracy,
            'weak_areas' => $result->weak_areas ?? [],
            'recommendations' => $this->getQuizRecommendations($accuracy, $result->weak_areas ?? []),
            'time_analysis' => $this->analyzeTimeSpent($result->time_spent_seconds),
        ];

        return $feedback;
    }

    /**
     * Get summary text based on performance
     */
    private function getQuizSummary(float $accuracy, int $correct, int $total): string
    {
        if ($accuracy >= 90) {
            return "Excellent! You got $correct out of $total questions correct. Outstanding performance!";
        } elseif ($accuracy >= 75) {
            return "Great job! You got $correct out of $total questions correct. Keep practicing!";
        } elseif ($accuracy >= 60) {
            return "Good effort! You got $correct out of $total questions correct. Review weak areas.";
        } elseif ($accuracy >= 45) {
            return "You got $correct out of $total questions correct. Focus on the weak areas identified below.";
        } else {
            return "You got $correct out of $total questions correct. Review the lesson content thoroughly.";
        }
    }

    /**
     * Get recommendations based on quiz performance
     */
    private function getQuizRecommendations(float $accuracy, array $weakAreas): array
    {
        $recommendations = [];

        if ($accuracy >= 90) {
            $recommendations[] = "You're mastering this topic! Consider taking the advanced level quiz.";
        } elseif ($accuracy >= 75) {
            $recommendations[] = "You have a good grasp. Review the weak areas for complete mastery.";
        } elseif ($accuracy >= 60) {
            $recommendations[] = "Review the lesson content focusing on: " . implode(', ', array_slice($weakAreas, 0, 2));
        } else {
            $recommendations[] = "Go back and carefully review the lesson content.";
            $recommendations[] = "Focus on understanding: " . implode(', ', $weakAreas);
            $recommendations[] = "Try the practice exercises before retaking the quiz.";
        }

        return $recommendations;
    }

    /**
     * Analyze time spent on quiz
     */
    private function analyzeTimeSpent(int $seconds): array
    {
        $minutes = intval($seconds / 60);
        $remainingSeconds = $seconds % 60;

        $analysis = [
            'formatted_time' => "{$minutes}m {$remainingSeconds}s",
            'seconds' => $seconds,
        ];

        if ($seconds < 60) {
            $analysis['note'] = "You completed the quiz very quickly. Consider taking more time to review questions.";
        } elseif ($seconds > 600) {
            $analysis['note'] = "You took a long time. Try to work faster while maintaining accuracy.";
        } else {
            $analysis['note'] = "Good pace!";
        }

        return $analysis;
    }

    /**
     * Generate feedback for exercise submission
     */
    public function generateExerciseFeedback(ExerciseSubmission $submission): array
    {
        $feedback = [
            'status' => $submission->score >= 70 ? 'pass' : 'needs_improvement',
            'score' => $submission->score,
            'max_score' => 100,
            'feedback' => $submission->feedback,
            'explanation' => $submission->explanation,
            'weak_areas' => $submission->weak_areas ?? [],
            'time_spent' => $this->formatTime($submission->time_spent_seconds),
            'difficulty_level' => $submission->difficulty_level,
            'recommendations' => $this->getExerciseRecommendations($submission),
        ];

        return $feedback;
    }

    /**
     * Get recommendations for exercise
     */
    private function getExerciseRecommendations(ExerciseSubmission $submission): array
    {
        $recommendations = [];

        if ($submission->score >= 85) {
            $recommendations[] = "Excellent work! You've mastered this exercise.";
        } elseif ($submission->score >= 70) {
            $recommendations[] = "Good! You passed the exercise. Review weak areas for better understanding.";
        } else {
            $recommendations[] = "Practice more on this exercise to improve.";
            if (!empty($submission->weak_areas)) {
                $recommendations[] = "Focus on: " . implode(', ', $submission->weak_areas);
            }
        }

        // Time analysis
        if ($submission->time_spent_seconds > 600) {
            $recommendations[] = "Try to work faster on similar exercises.";
        }

        return $recommendations;
    }

    /**
     * Format seconds to readable time
     */
    private function formatTime(int $seconds): string
    {
        $hours = intval($seconds / 3600);
        $minutes = intval(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m {$secs}s";
        } elseif ($minutes > 0) {
            return "{$minutes}m {$secs}s";
        } else {
            return "{$secs}s";
        }
    }

    /**
     * Compare performance with other students (anonymized)
     */
    public function comparePerformanceWithClass(UserQuizResult $result): array
    {
        $quizId = $result->quiz_id;
        
        $allResults = UserQuizResult::where('quiz_id', $quizId)->get();
        
        $averageScore = $allResults->avg('score');
        $medianScore = $allResults->sortBy('score')->median('score');
        $topScore = $allResults->max('score');

        return [
            'your_score' => $result->score,
            'class_average' => round($averageScore, 2),
            'median_score' => round($medianScore, 2),
            'top_score' => $topScore,
            'percentile' => $this->calculatePercentile($result->score, $allResults),
            'comparison' => $result->score > $averageScore ? 'above_average' : 'below_average',
        ];
    }

    /**
     * Calculate percentile ranking
     */
    private function calculatePercentile(int $score, $results): int
    {
        $totalCount = $results->count();
        $belowScore = $results->filter(function ($r) use ($score) {
            return $r->score < $score;
        })->count();

        return intval(($belowScore / $totalCount) * 100);
    }
}
