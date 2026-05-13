<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\UserNotification;
use Carbon\Carbon;

class GamificationService
{
    /**
     * Award points to a user
     */
    public function awardPoints(User $user, int $points, string $reason = ''): void
    {
        $user->increment('total_points', $points);
        
        // Check if user unlocked any badges
        $this->checkAndAwardBadges($user);
        
        // Update performance score
        $this->updatePerformanceScore($user);
    }

    /**
     * Update user's daily streak
     */
    public function updateDailyStreak(User $user): void
    {
        $today = now()->toDateString();
        $lastActivityDate = $user->last_activity_date?->toDateString();

        if ($lastActivityDate === $today) {
            // User already has activity today
            return;
        }

        $yesterday = now()->subDay()->toDateString();

        if ($lastActivityDate === $yesterday) {
            // Continue the streak
            $user->increment('daily_streak');
        } else {
            // Reset streak
            $user->update(['daily_streak' => 1]);
        }

        $user->update(['last_activity_date' => now()]);

        // Award streak bonus points
        if ($user->daily_streak % 7 === 0) {
            $this->awardPoints($user, 50, 'Weekly Streak Bonus');
            $this->notifyUser($user, 'streak', '🔥 Week Streak!', 'Amazing! You\'ve maintained a week-long streak. Keep it up!');
        }
    }

    /**
     * Check and award badges based on achievements
     */
    public function checkAndAwardBadges(User $user): void
    {
        $badges = Badge::all();

        foreach ($badges as $badge) {
            // Skip if user already has this badge
            if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }

            $shouldAward = false;

            switch ($badge->type) {
                case 'lesson':
                    $lessonsCompleted = $user->lessonProgress()
                        ->where('completion_percentage', 100)
                        ->count();
                    $shouldAward = $lessonsCompleted >= $badge->required_count;
                    break;

                case 'quiz':
                    $quizzesCompleted = $user->quizResults()->count();
                    $shouldAward = $quizzesCompleted >= $badge->required_count;
                    break;

                case 'streak':
                    $shouldAward = $user->daily_streak >= $badge->required_count;
                    break;

                case 'score':
                    $averageScore = $user->quizResults()
                        ->avg('score') ?? 0;
                    $shouldAward = $averageScore >= $badge->required_count;
                    break;

                case 'achievement':
                    // Custom achievement logic
                    $totalPoints = $user->total_points;
                    $shouldAward = $totalPoints >= $badge->required_count;
                    break;
            }

            if ($shouldAward) {
                UserBadge::create([
                    'user_id' => $user->id,
                    'badge_id' => $badge->id,
                    'earned_at' => now(),
                ]);

                // Award points for badge
                $user->increment('total_points', $badge->points_reward);

                // Notify user
                $this->notifyUser(
                    $user,
                    'badge',
                    "🏅 Badge Unlocked: {$badge->name}",
                    $badge->description
                );
            }
        }
    }

    /**
     * Update user's performance score
     */
    public function updatePerformanceScore(User $user): void
    {
        $quizResults = $user->quizResults;
        $exerciseSubmissions = $user->exerciseSubmissions;

        if ($quizResults->isEmpty() && $exerciseSubmissions->isEmpty()) {
            $user->update(['performance_score' => 0]);
            return;
        }

        $quizAverage = $quizResults->avg('score') ?? 0;
        $exerciseAverage = $exerciseSubmissions->avg('score') ?? 0;
        
        $performanceScore = (int) (($quizAverage * 0.6 + $exerciseAverage * 0.4));
        
        $user->update(['performance_score' => $performanceScore]);

        // Update user level based on performance
        $this->updateUserLevel($user);
    }

    /**
     * Update user's learning level
     */
    private function updateUserLevel(User $user): void
    {
        $score = $user->performance_score;

        if ($score < 40) {
            $level = 'beginner';
        } elseif ($score < 70) {
            $level = 'intermediate';
        } else {
            $level = 'advanced';
        }

        if ($user->level !== $level) {
            $user->update(['level' => $level]);
            $this->notifyUser($user, 'achievement', "🎓 Level Up: {$level}", "Congratulations! You've reached the {$level} level.");
        }
    }

    /**
     * Create a notification for user
     */
    public function notifyUser(User $user, string $type, string $title, string $message, ?string $actionUrl = null): UserNotification
    {
        return UserNotification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Get user's current leaderboard position
     */
    public function getLeaderboardPosition(User $user, string $type = 'all_time'): array
    {
        $leaderboard = \DB::table('users')
            ->select('users.id', 'users.name', 'users.total_points', 'users.performance_score')
            ->orderBy('total_points', 'desc')
            ->get();

        $position = $leaderboard->search(function ($user_data) use ($user) {
            return $user_data->id === $user->id;
        });

        return [
            'rank' => $position !== false ? $position + 1 : 0,
            'total_users' => $leaderboard->count(),
            'points' => $user->total_points,
            'performance_score' => $user->performance_score,
        ];
    }
}
