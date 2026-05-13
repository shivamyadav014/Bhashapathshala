<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\User;
use App\Models\UserQuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'published_courses' => Course::where('is_published', true)->count(),
            'total_lessons' => Lesson::count(),
            'total_exercises' => Exercise::count(),
            'total_quizzes' => Quiz::count(),
            'total_enrollments' => CourseEnrollment::count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_quiz_attempts' => UserQuizResult::count(),
            'average_quiz_score' => UserQuizResult::avg('score') ?? 0,
        ];

        $recentUsers = User::latest()->take(10)->get();
        $activeCourses = Course::with('instructor')
            ->withCount(['lessons', 'quizzes', 'enrollments'])
            ->where('is_published', true)
            ->orderByDesc('rating')
            ->take(10)
            ->get();

        $topCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->orderByDesc('rating')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'activeCourses', 'topCourses'));
    }

    public function courses(Request $request)
    {
        $query = Course::with('instructor')
            ->withCount(['lessons', 'quizzes', 'enrollments']);

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('language', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->input('status') === 'published');
        }

        if ($request->filled('language')) {
            $query->where('language', $request->input('language'));
        }

        $courses = $query->orderBy('title')->paginate(15)->withQueryString();
        $languages = Course::select('language')->distinct()->orderBy('language')->pluck('language');

        return view('admin.courses', compact('courses', 'languages'));
    }

    public function users(Request $request)
    {
        $query = User::withCount(['enrollments', 'quizResults']);

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function reports()
    {
        $userStats = [
            'total' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'instructors' => User::where('role', 'instructor')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        $courseStats = [
            'total' => Course::count(),
            'published' => Course::where('is_published', true)->count(),
            'draft' => Course::where('is_published', false)->count(),
        ];

        $quizStats = [
            'total_attempts' => UserQuizResult::count(),
            'average_score' => round(UserQuizResult::avg('score') ?? 0, 2),
            'pass_rate' => round((UserQuizResult::where('passed', true)->count() / max(UserQuizResult::count(), 1)) * 100, 2),
        ];

        $contentStats = [
            'lessons' => Lesson::count(),
            'exercises' => Exercise::count(),
            'quizzes' => Quiz::count(),
            'questions' => DB::table('quiz_questions')->count(),
        ];

        $languageStats = Course::select('language')
            ->selectRaw('count(*) as total')
            ->selectRaw('sum(case when is_published = 1 then 1 else 0 end) as published')
            ->groupBy('language')
            ->orderBy('language')
            ->get();

        $topCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->orderBy('title')
            ->take(8)
            ->get();

        return view('admin.reports', compact('userStats', 'courseStats', 'quizStats', 'contentStats', 'languageStats', 'topCourses'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,instructor,student',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', "Role updated for {$user->name}");
    }

    public function updateCourseStatus(Request $request, Course $course)
    {
        $validated = $request->validate([
            'is_published' => 'required|boolean',
        ]);

        $course->update([
            'is_published' => (bool) $validated['is_published'],
        ]);

        $status = $course->is_published ? 'published' : 'unpublished';

        return redirect()
            ->route('admin.courses', $request->only(['search', 'status', 'language', 'page']))
            ->with('success', "{$course->title} is now {$status}.");
    }
}
