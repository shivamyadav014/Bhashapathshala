<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function browse()
    {
        $courses = Course::with('instructor')
            ->withCount([
                'lessons as published_lessons_count' => fn ($q) => $q->where('is_published', true),
                'quizzes as published_quizzes_count' => fn ($q) => $q->where('is_published', true),
            ])
            ->where('is_published', true)
            ->orderBy('title')
            ->get();

        return view('courses.index', compact('courses'));
    }

    public function showCourse(Request $request, Course $course)
    {
        if (! $course->is_published && ! $this->canManageCourse($course)) {
            abort(404);
        }

        $course->load([
            'instructor',
            'lessons' => fn ($q) => $q->where('is_published', true)->withCount('exercises')->orderBy('order'),
            'quizzes' => fn ($q) => $q->where('is_published', true),
        ]);

        $enrollment = Auth::check()
            ? Auth::user()->enrollments()->where('course_id', $course->id)->first()
            : null;

        $completedLessonIds = collect();
        if ($request->user() && $enrollment) {
            $lessonIds = $course->lessons->pluck('id');
            if ($lessonIds->isNotEmpty()) {
                $completedLessonIds = LessonProgress::query()
                    ->where('user_id', $request->user()->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->where('is_completed', true)
                    ->pluck('lesson_id');
            }
        }

        return view('courses.show', compact('course', 'enrollment', 'completedLessonIds'));
    }

    public function enroll(Request $request, Course $course)
    {
        if (! $course->is_published) {
            abort(404);
        }

        CourseEnrollment::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'course_id' => $course->id,
            ],
            [
                'status' => 'enrolled',
                'enrolled_at' => now(),
            ]
        );

        CourseEnrollment::syncProgressFromLessons($request->user(), $course);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'You are enrolled in this course.');
    }

    public function showLesson(Request $request, Lesson $lesson)
    {
        $lesson->load(['course.instructor', 'exercises']);

        if (! $lesson->is_published && ! $this->canManageCourse($lesson->course)) {
            abort(404);
        }

        $user = $request->user();

        if ($redirect = $this->studentMustEnrollOrRedirect($request, $lesson->course)) {
            return $redirect;
        }

        if ($user->role !== 'student' && ! $this->canManageCourse($lesson->course)) {
            abort(403);
        }

        $progress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        return view('lessons.show', compact('lesson', 'progress'));
    }

    public function completeLesson(Request $request, Lesson $lesson)
    {
        $user = $request->user();

        if ($redirect = $this->studentMustEnrollOrRedirect($request, $lesson->course)) {
            return $redirect;
        }

        if ($user->role !== 'student' && ! $this->canManageCourse($lesson->course)) {
            abort(403);
        }

        LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'is_completed' => true,
                'progress_percentage' => 100,
                'completed_at' => now(),
                'started_at' => now(),
            ]
        );

        CourseEnrollment::syncProgressFromLessons($user, $lesson->course);

        return redirect()
            ->route('lessons.show', $lesson)
            ->with('success', 'Lesson marked complete.');
    }

    public function showExercise(Request $request, Exercise $exercise)
    {
        $exercise->load('lesson.course');
        $course = $exercise->lesson->course;

        $user = $request->user();

        if ($redirect = $this->studentMustEnrollOrRedirect($request, $course)) {
            return $redirect;
        }

        if ($user->role !== 'student' && ! $this->canManageCourse($course)) {
            abort(403);
        }

        $submission = ExerciseSubmission::where('user_id', $user->id)
            ->where('exercise_id', $exercise->id)
            ->latest()
            ->first();

        return view('exercises.show', compact('exercise', 'submission'));
    }

    public function submitExercise(Request $request, Exercise $exercise)
    {
        $exercise->load('lesson.course');
        $user = $request->user();
        $course = $exercise->lesson->course;

        if ($redirect = $this->studentMustEnrollOrRedirect($request, $course)) {
            return $redirect;
        }

        if ($user->role !== 'student' && ! $this->canManageCourse($course)) {
            abort(403);
        }

        $validated = $request->validate([
            'submission_content' => 'required|string|max:65535',
        ]);

        ExerciseSubmission::create([
            'user_id' => $user->id,
            'exercise_id' => $exercise->id,
            'submission_content' => $validated['submission_content'],
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('exercises.show', $exercise)
            ->with('success', 'Submission received. Check back for instructor feedback and your score.');
    }

    public function showQuiz(Request $request, Quiz $quiz)
    {
        $quiz->load(['course', 'questions']);

        if (! $quiz->is_published && ! $this->canManageCourse($quiz->course)) {
            abort(404);
        }

        $user = $request->user();

        if ($redirect = $this->studentMustEnrollOrRedirect($request, $quiz->course)) {
            return $redirect;
        }

        if ($user->role !== 'student' && ! $this->canManageCourse($quiz->course)) {
            abort(403);
        }

        $questions = $quiz->questions->map(function ($q) {
            $options = $q->options;
            if (is_string($options)) {
                $decoded = json_decode($options, true);
                $options = is_array($decoded) ? $decoded : [];
            }
            if (! is_array($options)) {
                $options = [];
            }

            return [
                'id' => $q->id,
                'question' => $q->question,
                'question_type' => $q->question_type,
                'options' => $options,
                'points' => $q->points,
                'order' => $q->order,
            ];
        });

        return view('quizzes.show', compact('quiz', 'questions'));
    }

    public function submitQuiz(Request $request, Quiz $quiz)
    {
        $quiz->load('course');
        $user = $request->user();

        if ($redirect = $this->studentMustEnrollOrRedirect($request, $quiz->course)) {
            return $redirect;
        }

        if ($user->role !== 'student' && ! $this->canManageCourse($quiz->course)) {
            abort(403);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|string|max:65535',
        ]);

        $answers = [];
        foreach ($validated['answers'] as $questionId => $answer) {
            $answers[(int) $questionId] = $answer ?? '';
        }

        $eval = $quiz->evaluateAttempt($user, $answers);

        return view('quizzes.result', [
            'quiz' => $quiz->load('course'),
            'result' => $eval['result'],
            'feedback' => $eval['feedback'],
        ]);
    }

    public function quizHistory(Request $request, Quiz $quiz)
    {
        $quiz->load('course');
        $user = $request->user();

        if ($redirect = $this->studentMustEnrollOrRedirect($request, $quiz->course)) {
            return $redirect;
        }

        if ($user->role !== 'student' && ! $this->canManageCourse($quiz->course)) {
            abort(403);
        }

        $results = $user->quizResults()
            ->where('quiz_id', $quiz->id)
            ->orderByDesc('completed_at')
            ->get();

        return view('quizzes.history', compact('quiz', 'results'));
    }

    /**
     * Students must enroll before lessons, exercises, or quizzes. Others (admin, course instructor) pass through.
     */
    protected function studentMustEnrollOrRedirect(Request $request, Course $course): ?RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return null;
        }

        if ($user->role === 'instructor' && (int) $user->id === (int) $course->instructor_id) {
            return null;
        }

        if ($user->role !== 'student') {
            return null;
        }

        if ($user->enrollments()->where('course_id', $course->id)->exists()) {
            return null;
        }

        return redirect()
            ->route('courses.show', $course)
            ->with('error', 'Enroll in this course first — then you can open lessons, exercises, and quizzes.');
    }

    protected function canManageCourse(Course $course): bool
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $user->role === 'instructor' && (int) $user->id === (int) $course->instructor_id;
    }
}
