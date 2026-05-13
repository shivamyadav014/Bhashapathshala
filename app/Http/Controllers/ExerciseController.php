<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function indexByLesson($lessonId)
    {
        $exercises = Exercise::where('lesson_id', $lessonId)->get();

        return response()->json($exercises);
    }

    public function show($id)
    {
        $exercise = Exercise::with('lesson')->find($id);

        if (!$exercise) {
            return response()->json(['error' => 'Exercise not found'], 404);
        }

        return response()->json($exercise);
    }

    public function store(Request $request)
    {
        $this->authorizeIsInstructor($request->user());

        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'exercise_type' => 'required|in:listening,speaking,reading,writing,matching,multiple_choice',
            'content' => 'required|string',
            'instructions' => 'nullable|string',
            'hints' => 'nullable|array',
            'difficulty_level' => 'nullable|integer|between:1,5',
            'points' => 'nullable|integer|min:1',
        ]);

        $exercise = Exercise::create($validated);

        return response()->json([
            'message' => 'Exercise created successfully',
            'exercise' => $exercise,
        ], 201);
    }

    public function submit(Request $request, $id)
    {
        $exercise = Exercise::find($id);

        if (!$exercise) {
            return response()->json(['error' => 'Exercise not found'], 404);
        }

        $validated = $request->validate([
            'submission_content' => 'required|string',
        ]);

        $submission = ExerciseSubmission::create([
            'user_id' => $request->user()->id,
            'exercise_id' => $id,
            'submission_content' => $validated['submission_content'],
            'submitted_at' => now(),
        ]);

        return response()->json([
            'message' => 'Exercise submitted successfully',
            'submission' => $submission,
        ], 201);
    }

    public function getSubmission(Request $request, $id)
    {
        $submission = ExerciseSubmission::where('user_id', $request->user()->id)
            ->where('exercise_id', $id)
            ->latest()
            ->first();

        if (!$submission) {
            return response()->json(['error' => 'No submission found'], 404);
        }

        return response()->json($submission);
    }

    public function getSubmissions(Request $request, $exerciseId)
    {
        $this->authorizeIsInstructor($request->user());

        $submissions = ExerciseSubmission::where('exercise_id', $exerciseId)
            ->with('user')
            ->paginate(20);

        return response()->json($submissions);
    }

    public function gradeSubmission(Request $request, $submissionId)
    {
        $this->authorizeIsInstructor($request->user());

        $submission = ExerciseSubmission::find($submissionId);

        if (!$submission) {
            return response()->json(['error' => 'Submission not found'], 404);
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'feedback' => 'nullable|string',
        ]);

        $submission->gradeSubmission($validated['score'], $validated['feedback'] ?? '');

        return response()->json([
            'message' => 'Submission graded successfully',
            'submission' => $submission,
        ]);
    }

    public function getStats($exerciseId)
    {
        $exercise = Exercise::with('submissions')->find($exerciseId);

        if (!$exercise) {
            return response()->json(['error' => 'Exercise not found'], 404);
        }

        return response()->json([
            'completion_count' => $exercise->getCompletionCount(),
            'average_score' => $exercise->getAverageScore(),
            'total_submissions' => $exercise->submissions()->count(),
        ]);
    }
}
