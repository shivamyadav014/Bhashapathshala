<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin');
    }

    /**
     * Get admin dashboard overview
     */
    public function getDashboardOverview()
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_lessons' => Lesson::count(),
            'total_quizzes' => Quiz::count(),
            'active_users_today' => User::where('last_activity_date', now()->toDateString())->count(),
            'total_points_distributed' => User::sum('total_points'),
        ];

        return response()->json($stats);
    }

    /**
     * List all users with filters
     */
    public function listUsers(Request $request)
    {
        $query = User::select('id', 'name', 'email', 'role', 'level', 'total_points', 'created_at');

        if ($request->has('role')) {
            $query->where('role', $request->get('role'));
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->paginate(20);

        return response()->json($users);
    }

    /**
     * Get user details with analytics
     */
    public function getUserDetails(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'user' => $user,
            'stats' => [
                'enrolled_courses' => $user->enrolledCourses()->count(),
                'total_points' => $user->total_points,
                'daily_streak' => $user->daily_streak,
                'level' => $user->level,
                'badges_count' => $user->badges()->count(),
                'quiz_attempts' => $user->quizResults()->count(),
                'exercise_submissions' => $user->exerciseSubmissions()->count(),
            ],
        ]);
    }

    /**
     * Create a badge
     */
    public function createBadge(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'slug' => 'required|string|unique:badges',
            'type' => 'required|in:lesson,quiz,streak,score,achievement',
            'required_count' => 'required|integer|min:1',
            'points_reward' => 'required|integer|min:0',
        ]);

        $badge = Badge::create($validated);

        return response()->json([
            'message' => 'Badge created successfully',
            'badge' => $badge,
        ], 201);
    }

    /**
     * Update a badge
     */
    public function updateBadge(Request $request, $badgeId)
    {
        $badge = Badge::find($badgeId);

        if (!$badge) {
            return response()->json(['error' => 'Badge not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'icon' => 'nullable|string',
            'required_count' => 'sometimes|integer|min:1',
            'points_reward' => 'sometimes|integer|min:0',
        ]);

        $badge->update($validated);

        return response()->json([
            'message' => 'Badge updated successfully',
            'badge' => $badge,
        ]);
    }

    /**
     * Delete a badge
     */
    public function deleteBadge($badgeId)
    {
        $badge = Badge::find($badgeId);

        if (!$badge) {
            return response()->json(['error' => 'Badge not found'], 404);
        }

        $badge->delete();

        return response()->json(['message' => 'Badge deleted successfully']);
    }

    /**
     * List all badges
     */
    public function listBadges(Request $request)
    {
        $type = $request->get('type');
        $query = Badge::query();

        if ($type) {
            $query->where('type', $type);
        }

        $badges = $query->paginate(20);

        return response()->json($badges);
    }

    /**
     * Get platform statistics
     */
    public function getPlatformStats()
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'students' => User::where('role', 'student')->count(),
                'instructors' => User::where('role', 'instructor')->count(),
                'admins' => User::where('role', 'admin')->count(),
                'by_level' => User::select('level')->selectRaw('count(*) as count')->groupBy('level')->get(),
            ],
            'courses' => [
                'total' => Course::count(),
                'published' => Course::where('is_published', true)->count(),
                'draft' => Course::where('is_published', false)->count(),
            ],
            'content' => [
                'lessons' => Lesson::count(),
                'quizzes' => Quiz::count(),
            ],
            'engagement' => [
                'active_users_today' => User::where('last_activity_date', now()->toDateString())->count(),
                'total_points_in_system' => User::sum('total_points'),
                'badges_earned' => \App\Models\UserBadge::count(),
            ],
        ];

        return response()->json($stats);
    }

    /**
     * Reset user progress
     */
    public function resetUserProgress(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->update([
            'total_points' => 0,
            'daily_streak' => 0,
            'performance_score' => 0,
            'level' => 'beginner',
        ]);

        $user->badges()->detach();
        $user->lessonProgress()->delete();
        $user->exerciseSubmissions()->delete();
        $user->quizResults()->delete();

        return response()->json(['message' => 'User progress reset successfully']);
    }

    /**
     * Generate reports
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:user_engagement,course_performance,learning_analytics',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $type = $request->get('type');
        $startDate = \Carbon\Carbon::parse($request->get('start_date'));
        $endDate = \Carbon\Carbon::parse($request->get('end_date'));

        $report = [];

        if ($type === 'user_engagement') {
            $report = $this->generateUserEngagementReport($startDate, $endDate);
        } elseif ($type === 'course_performance') {
            $report = $this->generateCoursePerformanceReport($startDate, $endDate);
        } elseif ($type === 'learning_analytics') {
            $report = $this->generateLearningAnalyticsReport($startDate, $endDate);
        }

        return response()->json($report);
    }

    private function generateUserEngagementReport($startDate, $endDate)
    {
        return [
            'type' => 'user_engagement',
            'period' => ['start' => $startDate, 'end' => $endDate],
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'active_users' => User::whereBetween('last_activity_date', [$startDate, $endDate])->count(),
            'points_distributed' => User::whereBetween('updated_at', [$startDate, $endDate])->sum('total_points'),
        ];
    }

    private function generateCoursePerformanceReport($startDate, $endDate)
    {
        $courses = Course::with('lessons')->get();

        return [
            'type' => 'course_performance',
            'period' => ['start' => $startDate, 'end' => $endDate],
            'courses' => $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'enrollments' => $course->enrollments()->count(),
                    'lessons' => $course->lessons()->count(),
                ];
            }),
        ];
    }

    private function generateLearningAnalyticsReport($startDate, $endDate)
    {
        return [
            'type' => 'learning_analytics',
            'period' => ['start' => $startDate, 'end' => $endDate],
            'total_quizzes_taken' => \App\Models\UserQuizResult::whereBetween('created_at', [$startDate, $endDate])->count(),
            'average_quiz_score' => \App\Models\UserQuizResult::whereBetween('created_at', [$startDate, $endDate])->avg('score'),
            'total_exercises_submitted' => \App\Models\ExerciseSubmission::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];
    }
}
