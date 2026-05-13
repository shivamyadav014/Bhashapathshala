<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function indexByCourse($courseId)
    {
        $lessons = Lesson::where('course_id', $courseId)
            ->where('is_published', true)
            ->orderBy('order')
            ->get();

        return response()->json($lessons);
    }

    public function show($id)
    {
        $lesson = Lesson::with(['course', 'exercises'])->find($id);

        if (!$lesson) {
            return response()->json(['error' => 'Lesson not found'], 404);
        }

        return response()->json($lesson);
    }

    public function store(Request $request)
    {
        $this->authorizeIsInstructor($request->user());

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'notes' => 'nullable|string',
            'order' => 'required|integer',
            'duration_minutes' => 'nullable|integer',
            'video_url' => 'nullable|url',
        ]);

        $lesson = Lesson::create($validated);

        return response()->json([
            'message' => 'Lesson created successfully',
            'lesson' => $lesson,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json(['error' => 'Lesson not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'notes' => 'nullable|string',
            'order' => 'nullable|integer',
            'duration_minutes' => 'nullable|integer',
            'video_url' => 'nullable|url',
            'is_published' => 'nullable|boolean',
        ]);

        $lesson->update($validated);

        return response()->json([
            'message' => 'Lesson updated successfully',
            'lesson' => $lesson,
        ]);
    }

    public function markAsCompleted(Request $request, $id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json(['error' => 'Lesson not found'], 404);
        }

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'lesson_id' => $id,
            ],
            [
                'is_completed' => true,
                'progress_percentage' => 100,
                'completed_at' => now(),
                'started_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Lesson marked as completed',
            'progress' => $progress,
        ]);
    }

    public function getProgress(Request $request, $id)
    {
        $progress = LessonProgress::where('user_id', $request->user()->id)
            ->where('lesson_id', $id)
            ->first();

        if (!$progress) {
            return response()->json(['error' => 'No progress found for this lesson'], 404);
        }

        return response()->json($progress);
    }

    public function updateProgress(Request $request, $id)
    {
        $validated = $request->validate([
            'progress_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'lesson_id' => $id,
            ],
            [
                'progress_percentage' => $validated['progress_percentage'],
                'started_at' => now(),
            ]
        );

        return response()->json($progress);
    }
}
