<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class CourseContentSeeder extends Seeder
{
    public function run(): void
    {
        Course::where('is_published', true)
            ->orderBy('id')
            ->get()
            ->each(function (Course $course): void {
                $this->ensureLessons($course);
                $this->ensureQuiz($course);

                $course->update([
                    'total_lessons' => $course->lessons()->where('is_published', true)->count(),
                ]);
            });
    }

    private function ensureLessons(Course $course): void
    {
        $existing = $course->lessons()->count();
        $language = $course->language;
        $level = $course->level;

        $lessons = [
            [
                'title' => "{$language} Essentials and Pronunciation",
                'content' => "Start with the sound and rhythm of {$language}. This lesson introduces useful greetings, pronunciation habits, and a short daily speaking routine.\n\nPractice plan:\n1. Read each phrase aloud three times.\n2. Record yourself once and listen for clarity.\n3. Use one phrase in a short self-introduction.\n\nBy the end, you should feel comfortable starting a simple {$language} conversation.",
                'notes' => "Focus on clarity before speed. For {$level} learners, steady repetition is more valuable than memorizing too many phrases at once.",
                'duration_minutes' => 25,
            ],
            [
                'title' => "Everyday {$language} Vocabulary",
                'content' => "Build vocabulary around daily life: people, places, food, travel, and common classroom words.\n\nTry grouping words by situation instead of memorizing a long list. For example, create one mini-list for ordering food and another for asking directions.\n\nMini task: write five short sentences using new words from this lesson.",
                'notes' => 'Use spaced review: today, tomorrow, then three days later.',
                'duration_minutes' => 30,
            ],
            [
                'title' => "{$language} Conversation Practice",
                'content' => "Turn vocabulary into conversation. This lesson gives you short question-and-answer patterns for real situations.\n\nPractice:\n- Introduce yourself.\n- Ask one polite question.\n- Answer with a complete sentence.\n- Close the conversation naturally.\n\nRepeat the dialogue until it feels smooth.",
                'notes' => 'Speak in full sentences, even when a one-word answer would work.',
                'duration_minutes' => 35,
            ],
        ];

        foreach ($lessons as $index => $lesson) {
            $order = $index + 1;

            if ($existing >= $order) {
                continue;
            }

            Lesson::create([
                'course_id' => $course->id,
                'title' => $lesson['title'],
                'content' => $lesson['content'],
                'notes' => $lesson['notes'],
                'order' => $order,
                'duration_minutes' => $lesson['duration_minutes'],
                'video_url' => null,
                'cover_image' => $course->thumbnail,
                'is_published' => true,
            ]);
        }
    }

    private function ensureQuiz(Course $course): void
    {
        if ($course->quizzes()->exists()) {
            return;
        }

        $language = $course->language;

        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => "{$language} Course Checkpoint",
            'description' => "A short checkpoint quiz covering the core lessons in {$course->title}.",
            'passing_score' => 70,
            'total_questions' => 5,
            'time_limit_minutes' => 15,
            'show_results_immediately' => true,
            'is_published' => true,
        ]);

        $questions = [
            [
                'question' => "What is the best first habit when learning {$language}?",
                'options' => ['Practice a little every day', 'Only memorize grammar tables', 'Skip pronunciation', 'Avoid speaking'],
                'correct_answer' => 'Practice a little every day',
                'explanation' => 'Short daily practice builds recall and confidence.',
            ],
            [
                'question' => "Vocabulary is easier to remember when grouped by real situations.",
                'question_type' => 'true_false',
                'options' => null,
                'correct_answer' => 'true',
                'explanation' => 'Situational groups make words easier to use in conversation.',
            ],
            [
                'question' => "Which activity helps improve {$language} pronunciation?",
                'options' => ['Recording yourself', 'Never listening aloud', 'Reading silently only', 'Skipping review'],
                'correct_answer' => 'Recording yourself',
                'explanation' => 'Recording helps you notice sounds and rhythm.',
            ],
            [
                'question' => 'A good conversation practice routine should include:',
                'options' => ['Questions and full-sentence answers', 'Only isolated words', 'No repetition', 'Only translation apps'],
                'correct_answer' => 'Questions and full-sentence answers',
                'explanation' => 'Full sentences help you move from recognition to communication.',
            ],
            [
                'question' => "Write the language name for this course.",
                'question_type' => 'short_answer',
                'options' => null,
                'correct_answer' => $language,
                'explanation' => "This course teaches {$language}.",
            ],
        ];

        foreach ($questions as $index => $question) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $question['question'],
                'question_type' => $question['question_type'] ?? 'multiple_choice',
                'options' => $question['options'] ?? null,
                'correct_answer' => $question['correct_answer'],
                'explanation' => $question['explanation'],
                'points' => 1,
                'order' => $index + 1,
            ]);
        }

        $quiz->update([
            'total_questions' => $quiz->questions()->count(),
        ]);
    }
}
