<?php

namespace App\Http\Controllers;

use App\Models\CourseEnrollment;
use App\Models\LessonProgress;
use App\Models\UserQuizResult;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function getDashboard(Request $request)
    {
        $user = $request->user();
        $enrollments = $user->enrollments()->with('course')->get();
        
        $stats = [
            'total_courses' => $enrollments->count(),
            'completed_courses' => $enrollments->where('status', 'completed')->count(),
            'in_progress_courses' => $enrollments->where('status', 'in_progress')->count(),
            'average_completion' => $enrollments->avg('completion_percentage'),
            'total_quiz_attempts' => UserQuizResult::where('user_id', $user->id)->count(),
            'quiz_pass_rate' => $this->calculatePassRate($user->id),
        ];

        return response()->json([
            'stats' => $stats,
            'enrollments' => $enrollments,
        ]);
    }

    public function getCourseProgress(Request $request, $courseId)
    {
        $enrollment = CourseEnrollment::where('user_id', $request->user()->id)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'Not enrolled in this course'], 404);
        }

        $course = $enrollment->course;
        $lessons = $course->lessons()->get();
        
        $lessonProgress = LessonProgress::where('user_id', $request->user()->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->get();

        $completedLessons = $lessonProgress->where('is_completed', true)->count();
        $totalLessons = $lessons->count();

        $quizResults = UserQuizResult::whereHas('quiz', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })
        ->where('user_id', $request->user()->id)
        ->get();

        return response()->json([
            'enrollment' => $enrollment,
            'lesson_progress' => [
                'completed' => $completedLessons,
                'total' => $totalLessons,
                'percentage' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0,
            ],
            'quiz_results' => [
                'total_attempts' => $quizResults->count(),
                'passed' => $quizResults->where('passed', true)->count(),
                'average_score' => $quizResults->avg('score') ?? 0,
            ],
        ]);
    }

    public function getLessonProgress(Request $request, $lessonId)
    {
        $progress = LessonProgress::where('user_id', $request->user()->id)
            ->where('lesson_id', $lessonId)
            ->first();

        if (!$progress) {
            return response()->json(['error' => 'No progress data found'], 404);
        }

        return response()->json($progress);
    }

    public function getQuizProgress(Request $request, $quizId)
    {
        $results = UserQuizResult::where('user_id', $request->user()->id)
            ->where('quiz_id', $quizId)
            ->orderBy('completed_at', 'desc')
            ->get();

        if ($results->isEmpty()) {
            return response()->json(['error' => 'No quiz attempts found'], 404);
        }

        return response()->json([
            'attempts' => $results,
            'summary' => [
                'total_attempts' => $results->count(),
                'best_score' => $results->max('score'),
                'latest_score' => $results->first()->score,
                'pass_rate' => round(($results->where('passed', true)->count() / $results->count()) * 100, 2),
            ],
        ]);
    }

    public function getPerformanceReport(Request $request)
    {
        $user = $request->user();
        $quizResults = UserQuizResult::where('user_id', $user->id)
            ->orderBy('completed_at', 'desc')
            ->get();

        $courseEnrollments = $user->enrollments()
            ->with('course')
            ->get();

        $report = [
            'user' => $user,
            'overall_stats' => [
                'total_courses' => $courseEnrollments->count(),
                'total_quiz_attempts' => $quizResults->count(),
                'average_quiz_score' => $quizResults->avg('score') ?? 0,
                'quizzes_passed' => $quizResults->where('passed', true)->count(),
                'pass_rate' => $quizResults->count() > 0 
                    ? round(($quizResults->where('passed', true)->count() / $quizResults->count()) * 100, 2)
                    : 0,
            ],
            'course_performance' => $courseEnrollments->map(function ($enrollment) {
                return [
                    'course_id' => $enrollment->course_id,
                    'course_title' => $enrollment->course->title,
                    'status' => $enrollment->status,
                    'completion_percentage' => $enrollment->completion_percentage,
                ];
            }),
            'recent_quiz_results' => $quizResults->take(10),
        ];

        return response()->json($report);
    }

    private function calculatePassRate($userId)
    {
        $results = UserQuizResult::where('user_id', $userId)->get();
        if ($results->count() === 0) {
            return 0;
        }
        return round(($results->where('passed', true)->count() / $results->count()) * 100, 2);
    }
}
