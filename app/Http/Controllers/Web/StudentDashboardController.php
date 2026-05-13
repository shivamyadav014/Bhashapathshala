<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ExerciseSubmission;
use App\Models\LessonProgress;
use App\Models\UserQuizResult;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $enrollments = $user->enrollments()->with('course.instructor')->get();

        $stats = [
            'courses_enrolled' => $enrollments->count(),
            'courses_completed' => $enrollments->where('status', 'completed')->count(),
            'avg_completion' => round((float) $enrollments->avg('completion_percentage'), 1),
            'lessons_completed' => LessonProgress::where('user_id', $user->id)->where('is_completed', true)->count(),
            'quiz_attempts' => UserQuizResult::where('user_id', $user->id)->count(),
            'quiz_pass_rate' => $this->quizPassRate($user->id),
        ];

        return view('student.dashboard', compact('enrollments', 'stats'));
    }

    public function myCourses(Request $request)
    {
        $enrollments = $request->user()
            ->enrollments()
            ->with('course.instructor')
            ->orderByDesc('enrolled_at')
            ->paginate(10);

        return view('student.my-courses', compact('enrollments'));
    }

    public function performance(Request $request)
    {
        $user = $request->user();

        $quizResults = UserQuizResult::where('user_id', $user->id)
            ->with('quiz.course')
            ->orderByDesc('completed_at')
            ->paginate(15);

        $overall = [
            'avg_score' => round((float) UserQuizResult::where('user_id', $user->id)->avg('score'), 2),
            'passed' => UserQuizResult::where('user_id', $user->id)->where('passed', true)->count(),
            'attempts' => UserQuizResult::where('user_id', $user->id)->count(),
        ];

        $enrollments = $user->enrollments()->with('course')->get();

        $exerciseSubmissions = ExerciseSubmission::where('user_id', $user->id)
            ->with(['exercise.lesson.course'])
            ->orderByDesc('submitted_at')
            ->paginate(10, ['*'], 'exercise_page');

        $gradedExercise = ExerciseSubmission::where('user_id', $user->id)
            ->whereNotNull('score')
            ->with('exercise')
            ->get();

        $exercisePercents = $gradedExercise->map(fn (ExerciseSubmission $s) => $s->getPercentageScore())->filter();

        $exerciseOverall = [
            'submissions' => ExerciseSubmission::where('user_id', $user->id)->count(),
            'graded' => $gradedExercise->count(),
            'avg_percent' => $exercisePercents->isEmpty()
                ? null
                : round((float) $exercisePercents->avg(), 1),
        ];

        $insights = $this->buildPerformanceInsights($overall, $exerciseOverall, $enrollments);

        return view('student.performance', compact(
            'quizResults',
            'overall',
            'enrollments',
            'exerciseSubmissions',
            'exerciseOverall',
            'insights'
        ));
    }

    /**
     * Short narrative feedback lines for the performance report.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\CourseEnrollment>  $enrollments
     * @return array<int, string>
     */
    private function buildPerformanceInsights(array $overall, array $exerciseOverall, $enrollments): array
    {
        $lines = [];

        $avgCourse = round((float) $enrollments->avg('completion_percentage'), 1);
        if ($enrollments->isNotEmpty()) {
            if ($avgCourse >= 75) {
                $lines[] = 'You are moving steadily through your courses — maintain this pace and revisit earlier lessons if anything feels rusty.';
            } elseif ($avgCourse >= 40) {
                $lines[] = 'Course progress is in the middle range: finishing more lessons will lift both your completion rate and quiz confidence.';
            } else {
                $lines[] = 'Focus on completing the next lessons in order; small daily steps add up quickly.';
            }
        }

        if ($overall['attempts'] > 0) {
            $avg = $overall['avg_score'];
            if ($avg >= 80) {
                $lines[] = 'Quiz performance is strong — use quizzes to confirm retention before moving to harder material.';
            } elseif ($avg >= 60) {
                $lines[] = 'Quiz scores are acceptable; review explanations for questions you miss and retake where allowed.';
            } else {
                $lines[] = 'Quiz scores suggest more practice: repeat lessons and exercises before the next quiz attempt.';
            }

            $passRate = $overall['attempts'] > 0
                ? round(($overall['passed'] / $overall['attempts']) * 100, 1)
                : 0;
            if ($passRate < 50 && $overall['attempts'] >= 2) {
                $lines[] = 'Less than half of your recorded quiz attempts passed the course threshold — prioritize understanding over speed.';
            }
        } else {
            $lines[] = 'Take course quizzes to get scored feedback and track improvement over time.';
        }

        if ($exerciseOverall['submissions'] > 0) {
            if ($exerciseOverall['graded'] === 0) {
                $lines[] = 'Exercise submissions are waiting for instructor grading — you will see scores and comments here when they are marked.';
            } elseif ($exerciseOverall['avg_percent'] !== null) {
                $ap = $exerciseOverall['avg_percent'];
                if ($ap >= 75) {
                    $lines[] = 'Graded exercises show solid written work; keep applying instructor feedback on each submission.';
                } elseif ($ap >= 55) {
                    $lines[] = 'Exercise grades are improving room — read instructor feedback carefully and revise where suggested.';
                } else {
                    $lines[] = 'Exercise scores are low relative to full marks; compare your answers with lesson examples and ask for clarification if needed.';
                }
            }
        } else {
            $lines[] = 'Complete lesson exercises to receive personalized instructor feedback alongside your scores.';
        }

        return array_values(array_unique($lines));
    }

    private function quizPassRate(int $userId): float
    {
        $results = UserQuizResult::where('user_id', $userId)->get();
        if ($results->isEmpty()) {
            return 0;
        }

        return round(($results->where('passed', true)->count() / $results->count()) * 100, 2);
    }
}
