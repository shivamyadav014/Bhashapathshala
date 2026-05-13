<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Lesson badges
            [
                'name' => 'First Lesson',
                'description' => 'Complete your first lesson',
                'icon' => '📚',
                'slug' => 'first-lesson',
                'type' => 'lesson',
                'required_count' => 1,
                'points_reward' => 10,
            ],
            [
                'name' => 'Lesson Master',
                'description' => 'Complete 10 lessons',
                'icon' => '📖',
                'slug' => 'lesson-master',
                'type' => 'lesson',
                'required_count' => 10,
                'points_reward' => 50,
            ],
            [
                'name' => 'Course Champion',
                'description' => 'Complete 25 lessons',
                'icon' => '🏆',
                'slug' => 'course-champion',
                'type' => 'lesson',
                'required_count' => 25,
                'points_reward' => 100,
            ],

            // Quiz badges
            [
                'name' => 'Quiz Rookie',
                'description' => 'Take your first quiz',
                'icon' => '❓',
                'slug' => 'quiz-rookie',
                'type' => 'quiz',
                'required_count' => 1,
                'points_reward' => 15,
            ],
            [
                'name' => 'Quiz Expert',
                'description' => 'Take 10 quizzes',
                'icon' => '🎯',
                'slug' => 'quiz-expert',
                'type' => 'quiz',
                'required_count' => 10,
                'points_reward' => 75,
            ],

            // Streak badges
            [
                'name' => 'Week Warrior',
                'description' => 'Maintain a 7-day streak',
                'icon' => '🔥',
                'slug' => 'week-warrior',
                'type' => 'streak',
                'required_count' => 7,
                'points_reward' => 60,
            ],
            [
                'name' => 'Month Master',
                'description' => 'Maintain a 30-day streak',
                'icon' => '⭐',
                'slug' => 'month-master',
                'type' => 'streak',
                'required_count' => 30,
                'points_reward' => 200,
            ],

            // Score badges
            [
                'name' => 'Perfect Score',
                'description' => 'Average quiz score above 90%',
                'icon' => '💯',
                'slug' => 'perfect-score',
                'type' => 'score',
                'required_count' => 90,
                'points_reward' => 150,
            ],

            // Achievement badges
            [
                'name' => 'Point Collector',
                'description' => 'Earn 500 points',
                'icon' => '💰',
                'slug' => 'point-collector',
                'type' => 'achievement',
                'required_count' => 500,
                'points_reward' => 50,
            ],
            [
                'name' => 'Point Millionaire',
                'description' => 'Earn 5000 points',
                'icon' => '💎',
                'slug' => 'point-millionaire',
                'type' => 'achievement',
                'required_count' => 5000,
                'points_reward' => 200,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(
                ['slug' => $badge['slug']],
                $badge
            );
        }
    }
}
