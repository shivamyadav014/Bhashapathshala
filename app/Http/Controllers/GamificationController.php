<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Leaderboard;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class GamificationController extends Controller
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Get user's badges
     */
    public function getBadges(Request $request)
    {
        $user = $request->user();
        
        $badges = $user->badges()->with('pivot')->get()->map(function ($badge) {
            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'description' => $badge->description,
                'icon' => $badge->icon,
                'type' => $badge->type,
                'earned_at' => $badge->pivot->earned_at,
            ];
        });

        return response()->json([
            'total_badges' => $badges->count(),
            'badges' => $badges,
        ]);
    }

    /**
     * Get all available badges with progress
     */
    public function getAllBadgesWithProgress(Request $request)
    {
        $user = $request->user();
        $badges = Badge::all();

        $badgesWithProgress = $badges->map(function ($badge) use ($user) {
            $earned = $user->badges()->where('badge_id', $badge->id)->exists();
            
            $progress = 0;
            if (!$earned) {
                $progress = $this->calculateBadgeProgress($user, $badge);
            }

            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'description' => $badge->description,
                'icon' => $badge->icon,
                'type' => $badge->type,
                'required_count' => $badge->required_count,
                'points_reward' => $badge->points_reward,
                'earned' => $earned,
                'progress_percentage' => $earned ? 100 : $progress,
            ];
        });

        return response()->json($badgesWithProgress);
    }

    /**
     * Calculate badge progress
     */
    private function calculateBadgeProgress(User $user, Badge $badge): int
    {
        $current = 0;
        $required = $badge->required_count;

        switch ($badge->type) {
            case 'lesson':
                $current = $user->lessonProgress()
                    ->where('completion_percentage', 100)
                    ->count();
                break;
            case 'quiz':
                $current = $user->quizResults()->count();
                break;
            case 'streak':
                $current = $user->daily_streak;
                break;
            case 'score':
                $current = (int) ($user->quizResults()->avg('score') ?? 0);
                break;
            case 'achievement':
                $current = $user->total_points;
                break;
        }

        return min(100, intval(($current / $required) * 100));
    }

    /**
     * Get user's current points and stats
     */
    public function getPointsAndStats(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'total_points' => $user->total_points,
            'daily_streak' => $user->daily_streak,
            'level' => $user->level,
            'performance_score' => $user->performance_score,
            'badges_count' => $user->badges()->count(),
        ]);
    }

    /**
     * Get global leaderboard
     */
    public function getLeaderboard(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $type = $request->get('type', 'all_time'); // all_time, weekly, monthly

        $query = User::select('id', 'name', 'total_points', 'performance_score', 'level')
            ->orderBy('total_points', 'desc');

        if ($type === 'weekly') {
            $query->where('last_activity_date', '>=', now()->subWeek());
        } elseif ($type === 'monthly') {
            $query->where('last_activity_date', '>=', now()->subMonth());
        }

        $leaderboard = $query->paginate($perPage, ['*'], 'page', $page);

        $leaderboard->transform(function ($user, $index) use ($perPage, $page) {
            return [
                'rank' => (($page - 1) * $perPage) + $index + 1,
                'user_id' => $user->id,
                'name' => $user->name,
                'points' => $user->total_points,
                'performance_score' => $user->performance_score,
                'level' => $user->level,
            ];
        });

        $userRank = $this->gamificationService->getLeaderboardPosition($request->user(), $type);

        return response()->json([
            'leaderboard' => $leaderboard,
            'your_rank' => $userRank,
        ]);
    }

    /**
     * Get notifications
     */
    public function getNotifications(Request $request)
    {
        $user = $request->user();
        $unreadOnly = $request->get('unread_only', false);

        $query = UserNotification::where('user_id', $user->id);

        if ($unreadOnly) {
            $query->where('is_read', false);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($notifications);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead(Request $request, $notificationId)
    {
        $notification = UserNotification::where('id', $notificationId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read']);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead(Request $request)
    {
        UserNotification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount(Request $request)
    {
        $count = UserNotification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }
}
