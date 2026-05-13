<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('instructor')
            ->where('is_published', true)
            ->paginate(15);

        return response()->json($courses);
    }

    public function show($id)
    {
        $course = Course::with(['instructor', 'lessons', 'quizzes'])->find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return response()->json($course);
    }

    public function store(Request $request)
    {
        $this->authorizeIsInstructor($request->user());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'language' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'thumbnail' => 'nullable|url',
            'duration_hours' => 'nullable|integer',
        ]);

        $course = Course::create([
            ...$validated,
            'instructor_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'language' => 'nullable|string',
            'level' => 'nullable|in:beginner,intermediate,advanced',
            'thumbnail' => 'nullable|url',
            'duration_hours' => 'nullable|integer',
            'is_published' => 'nullable|boolean',
        ]);

        $course->update($validated);

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => $course,
        ]);
    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $this->authorize('delete', $course);

        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }

    public function enroll(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $enrollment = CourseEnrollment::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'course_id' => $id,
            ],
            [
                'status' => 'enrolled',
                'enrolled_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Enrolled in course successfully',
            'enrollment' => $enrollment,
        ], 201);
    }

    public function myEnrollments(Request $request)
    {
        $enrollments = $request->user()
            ->enrollments()
            ->with('course.instructor')
            ->paginate(10);

        return response()->json($enrollments);
    }

    public function getProgress(Request $request, $id)
    {
        $enrollment = CourseEnrollment::where('user_id', $request->user()->id)
            ->where('course_id', $id)
            ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'Not enrolled in this course'], 404);
        }

        return response()->json($enrollment);
    }
}
