<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonExerciseSeeder extends Seeder
{
    public function run(): void
    {
        Lesson::with(['course', 'exercises'])
            ->orderBy('course_id')
            ->orderBy('order')
            ->get()
            ->each(function (Lesson $lesson): void {
                if ($lesson->exercises->isNotEmpty()) {
                    return;
                }

                $language = $lesson->course->language;

                Exercise::create([
                    'lesson_id' => $lesson->id,
                    'title' => "{$language} Practice Response",
                    'description' => "Write a short answer using ideas from {$lesson->title}.",
                    'exercise_type' => 'writing',
                    'content' => "Write 5-6 sentences in or about {$language}. Include one greeting, two useful vocabulary words, and one complete question-and-answer pair.",
                    'instructions' => 'Keep it simple and focus on using the lesson vocabulary clearly. Submit your written response below.',
                    'hints' => [
                        'Start with a greeting or introduction.',
                        'Use short sentences before trying complex ones.',
                        'Read your answer aloud once before submitting.',
                    ],
                    'difficulty_level' => 2,
                    'points' => 12,
                ]);

                Exercise::create([
                    'lesson_id' => $lesson->id,
                    'title' => "{$language} Quick Check",
                    'description' => "Check that you understood the key idea from {$lesson->title}.",
                    'exercise_type' => 'multiple_choice',
                    'content' => json_encode([
                        'question' => 'What is the best way to remember this lesson?',
                        'options' => [
                            'Practice the phrases in a real situation',
                            'Read once and never review',
                            'Skip pronunciation practice',
                            'Only memorize the title',
                        ],
                        'answer' => 'Practice the phrases in a real situation',
                    ]),
                    'instructions' => 'Choose the best answer, then explain your choice in one sentence when submitting.',
                    'hints' => [
                        'Language learning improves through active use.',
                    ],
                    'difficulty_level' => 1,
                    'points' => 8,
                ]);
            });
    }
}
