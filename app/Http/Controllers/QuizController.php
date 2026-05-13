<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\UserQuizResult;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function indexByCourse($courseId)
    {
        $quizzes = Quiz::where('course_id', $courseId)
            ->where('is_published', true)
            ->get();

        return response()->json($quizzes);
    }

    public function show($id)
    {
        $quiz = Quiz::with('questions')->find($id);

        if (!$quiz) {
            return response()->json(['error' => 'Quiz not found'], 404);
        }

        return response()->json($quiz);
    }

    public function store(Request $request)
    {
        $this->authorizeIsInstructor($request->user());

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'passing_score' => 'nullable|integer|between:0,100',
            'time_limit_minutes' => 'nullable|integer',
            'show_results_immediately' => 'nullable|boolean',
        ]);

        $quiz = Quiz::create($validated);

        return response()->json([
            'message' => 'Quiz created successfully',
            'quiz' => $quiz,
        ], 201);
    }

    public function addQuestion(Request $request, $quizId)
    {
        $this->authorizeIsInstructor($request->user());

        $validated = $request->validate([
            'question' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer,essay',
            'options' => 'nullable|array',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
            'points' => 'nullable|integer|min:1',
            'order' => 'required|integer',
        ]);

        $question = QuizQuestion::create([
            ...$validated,
            'quiz_id' => $quizId,
        ]);

        return response()->json([
            'message' => 'Question added successfully',
            'question' => $question,
        ], 201);
    }

    public function submit(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->find($id);

        if (!$quiz) {
            return response()->json(['error' => 'Quiz not found'], 404);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
        ]);

        $eval = $quiz->evaluateAttempt($request->user(), $validated['answers']);

        return response()->json([
            'message' => 'Quiz submitted successfully',
            'result' => $eval['result'],
            'feedback' => $eval['feedback'],
        ], 201);
    }

    public function getResults(Request $request, $id)
    {
        $results = UserQuizResult::where('user_id', $request->user()->id)
            ->where('quiz_id', $id)
            ->orderBy('completed_at', 'desc')
            ->get();

        return response()->json($results);
    }

    public function getStats($quizId)
    {
        $quiz = Quiz::find($quizId);

        if (!$quiz) {
            return response()->json(['error' => 'Quiz not found'], 404);
        }

        return response()->json([
            'total_attempts' => $quiz->getTotalAttempts(),
            'average_score' => $quiz->getAverageScore(),
            'pass_rate' => $quiz->getPassRate(),
        ]);
    }

}
