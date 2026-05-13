<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class InstructorDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        $courses = Course::withCount('enrollments')
            ->where('instructor_id', $user->id)
            ->orderByDesc('updated_at')
            ->take(8)
            ->get();

        $stats = [
            'courses' => Course::where('instructor_id', $user->id)->count(),
            'published' => Course::where('instructor_id', $user->id)->where('is_published', true)->count(),
            'learners' => Course::where('instructor_id', $user->id)->withCount('enrollments')->get()->sum('enrollments_count'),
        ];

        return view('instructor.dashboard', compact('courses', 'stats'));
    }

    public function courses(Request $request)
    {
        $courses = Course::where('instructor_id', $request->user()->id)
            ->withCount('enrollments')
            ->orderBy('title')
            ->paginate(15);

        return view('instructor.courses', compact('courses'));
    }
}
