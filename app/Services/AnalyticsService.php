<?php

namespace App\Services;

use App\Models\ExerciseSubmission;
use App\Models\User;
use App\Models\UserPerformanceAnalytic;
use App\Models\UserQuizResult;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Calculate user's overall progress across all courses
     */
    public function getUserOverallProgress(User $user): array
    {
        $totalCourses = $user->enrolledCourses()->count();
        
        if ($totalCourses === 0) {
            return [
                'overall_progress' => 0,
                'total_courses' => 0,
                'courses_completed' => 0,
                'courses_in_progress' => 0,
            ];
        }

        $completedCourses = $user->enrollments()
            ->where('status', 'completed')
            ->count();
        
        $inProgressCourses = $user->enrollments()
            ->where('status', 'in_progress')
            ->count();

        $totalProgress = $user->enrollments()->avg('completion_percentage') ?? 0;

        return [
            'overall_progress' => round($totalProgress, 2),
            'total_courses' => $totalCourses,
            'courses_completed' => $completedCourses,
            'courses_in_progress' => $inProgressCourses,
        ];
    }

    /**
     * Get user's course-specific progress
     */
    public function getCourseProgress(User $user, $courseId): array
    {
        $enrollment = $user->enrollments()->where('course_id', $courseId)->first();
        
        if (!$enrollment) {
            return ['error' => 'Not enrolled in this course'];
        }

        $lessonsTotal = $enrollment->course->lessons()->count();
        $lessonsCompleted = $user->lessonProgress()
            ->whereIn('lesson_id', $enrollment->course->lessons()->pluck('id'))
            ->where('completion_percentage', 100)
            ->count();

        return [
            'course_id' => $courseId,
            'course_title' => $enrollment->course->title,
            'lessons_total' => $lessonsTotal,
            'lessons_completed' => $lessonsCompleted,
            'completion_percentage' => $enrollment->completion_percentage,
            'status' => $enrollment->status,
            'enrolled_at' => $enrollment->created_at,
        ];
    }

    /**
     * Calculate weak areas based on quiz and exercise performance
     */
    public function getWeakAreas(User $user, ?int $limit = 5): array
    {
        $weakAreas = [];

        // Get weak areas from quiz results
        $quizWeakAreas = UserQuizResult::where('user_id', $user->id)
            ->whereNotNull('weak_areas')
            ->get()
            ->pluck('weak_areas')
            ->collapse()
            ->countBy()
            ->sortDesc()
            ->slice(0, 3);

        // Get weak areas from exercise submissions
        $exerciseWeakAreas = ExerciseSubmission::where('user_id', $user->id)
            ->whereNotNull('weak_areas')
            ->get()
            ->pluck('weak_areas')
            ->collapse()
            ->countBy()
            ->sortDesc()
            ->slice(0, 3);

        // Merge and get top weak areas
        $allWeakAreas = collect($quizWeakAreas)->merge($exerciseWeakAreas)
            ->sortDesc()
            ->take($limit)
            ->keys();

        return $allWeakAreas->toArray();
    }

    /**
     * Generate personalized recommendations
     */
    public function getRecommendations(User $user): array
    {
        $recommendations = [];
        
        $weakAreas = $this->getWeakAreas($user);
        if (!empty($weakAreas)) {
            $recommendations[] = "Focus on improving: " . implode(', ', $weakAreas);
        }

        $quizAverage = $user->quizResults()->avg('score') ?? 0;
        if ($quizAverage < 60) {
            $recommendations[] = "Your quiz scores are below average. Consider reviewing the lesson content more carefully.";
        }

        if ($user->daily_streak === 0) {
            $recommendations[] = "Start a learning streak by practicing daily!";
        }

        $exerciseAverage = $user->exerciseSubmissions()->avg('score') ?? 0;
        if ($exerciseAverage < 70) {
            $recommendations[] = "Try completing more practice exercises to improve your skills.";
        }

        if (count($recommendations) === 0) {
            $recommendations[] = "Great job! Keep up the excellent work. Consider taking advanced courses.";
        }

        return $recommendations;
    }

    /**
     * Get daily performance analytics
     */
    public function recordDailyAnalytics(User $user, ?Carbon $date = null): UserPerformanceAnalytic
    {
        $date = $date ?? now();

        // Get today's stats
        $lessonsCompleted = $user->lessonProgress()
            ->where('updated_at', '>=', $date->startOfDay())
            ->where('updated_at', '<=', $date->endOfDay())
            ->where('completion_percentage', 100)
            ->count();

        $exercisesSubmitted = $user->exerciseSubmissions()
            ->where('created_at', '>=', $date->startOfDay())
            ->where('created_at', '<=', $date->endOfDay())
            ->count();

        $quizzesAttempted = $user->quizResults()
            ->where('created_at', '>=', $date->startOfDay())
            ->where('created_at', '<=', $date->endOfDay())
            ->count();

        $avgQuizScore = $user->quizResults()
            ->where('created_at', '>=', $date->startOfDay())
            ->where('created_at', '<=', $date->endOfDay())
            ->avg('score') ?? 0;

        $avgExerciseScore = $user->exerciseSubmissions()
            ->where('created_at', '>=', $date->startOfDay())
            ->where('created_at', '<=', $date->endOfDay())
            ->avg('score') ?? 0;

        $pointsEarned = $user->exerciseSubmissions()
            ->where('created_at', '>=', $date->startOfDay())
            ->where('created_at', '<=', $date->endOfDay())
            ->sum('score') ?? 0;

        return UserPerformanceAnalytic::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $date->toDateString(),
            ],
            [
                'lessons_completed' => $lessonsCompleted,
                'exercises_submitted' => $exercisesSubmitted,
                'quizzes_attempted' => $quizzesAttempted,
                'average_quiz_score' => round($avgQuizScore, 2),
                'average_exercise_score' => round($avgExerciseScore, 2),
                'points_earned' => $pointsEarned,
                'weak_areas' => $this->getWeakAreas($user),
            ]
        );
    }

    /**
     * Get analytics for a date range
     */
    public function getAnalyticsRange(User $user, Carbon $startDate, Carbon $endDate): array
    {
        $analytics = UserPerformanceAnalytic::where('user_id', $user->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        if ($analytics->isEmpty()) {
            return [];
        }

        return [
            'total_lessons_completed' => $analytics->sum('lessons_completed'),
            'total_exercises_submitted' => $analytics->sum('exercises_submitted'),
            'total_quizzes_attempted' => $analytics->sum('quizzes_attempted'),
            'average_quiz_score' => round($analytics->avg('average_quiz_score'), 2),
            'average_exercise_score' => round($analytics->avg('average_exercise_score'), 2),
            'total_points_earned' => $analytics->sum('points_earned'),
            'daily_data' => $analytics,
        ];
    }

    /**
     * Get performance trend (improving or declining)
     */
    public function getPerformanceTrend(User $user, int $days = 7): array
    {
        $startDate = now()->subDays($days);
        $endDate = now();

        $analytics = UserPerformanceAnalytic::where('user_id', $user->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date')
            ->get();

        if ($analytics->count() < 2) {
            return ['trend' => 'insufficient_data'];
        }

        $firstAverage = $analytics->first()->average_quiz_score;
        $lastAverage = $analytics->last()->average_quiz_score;
        
        $trend = $lastAverage > $firstAverage ? 'improving' : ($lastAverage < $firstAverage ? 'declining' : 'stable');

        return [
            'trend' => $trend,
            'first_average' => $firstAverage,
            'last_average' => $lastAverage,
            'change' => round($lastAverage - $firstAverage, 2),
        ];
    }
}
