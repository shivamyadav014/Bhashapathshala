<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use App\Services\FeedbackService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $analyticsService;
    protected $feedbackService;

    public function __construct(
        AnalyticsService $analyticsService,
        FeedbackService $feedbackService
    ) {
        $this->analyticsService = $analyticsService;
        $this->feedbackService = $feedbackService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Get overall progress
     */
    public function getOverallProgress(Request $request)
    {
        $user = $request->user();
        $progress = $this->analyticsService->getUserOverallProgress($user);

        return response()->json($progress);
    }

    /**
     * Get course-specific progress
     */
    public function getCourseProgress(Request $request, $courseId)
    {
        $user = $request->user();
        $progress = $this->analyticsService->getCourseProgress($user, $courseId);

        return response()->json($progress);
    }

    /**
     * Get weak areas
     */
    public function getWeakAreas(Request $request)
    {
        $user = $request->user();
        $limit = $request->get('limit', 5);
        
        $weakAreas = $this->analyticsService->getWeakAreas($user, $limit);

        return response()->json([
            'weak_areas' => $weakAreas,
        ]);
    }

    /**
     * Get personalized recommendations
     */
    public function getRecommendations(Request $request)
    {
        $user = $request->user();
        $recommendations = $this->analyticsService->getRecommendations($user);

        return response()->json([
            'recommendations' => $recommendations,
        ]);
    }

    /**
     * Get daily analytics
     */
    public function getDailyAnalytics(Request $request)
    {
        $user = $request->user();
        $date = $request->get('date') ? \Carbon\Carbon::parse($request->get('date')) : now();

        $analytics = $this->analyticsService->recordDailyAnalytics($user, $date);

        return response()->json($analytics);
    }

    /**
     * Get analytics for date range
     */
    public function getAnalyticsRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $user = $request->user();
        $startDate = \Carbon\Carbon::parse($request->get('start_date'));
        $endDate = \Carbon\Carbon::parse($request->get('end_date'));

        $analytics = $this->analyticsService->getAnalyticsRange($user, $startDate, $endDate);

        return response()->json($analytics);
    }

    /**
     * Get performance trend
     */
    public function getPerformanceTrend(Request $request)
    {
        $user = $request->user();
        $days = $request->get('days', 7);

        $trend = $this->analyticsService->getPerformanceTrend($user, $days);

        return response()->json($trend);
    }

    /**
     * Get comprehensive performance report
     */
    public function getPerformanceReport(Request $request)
    {
        $user = $request->user();

        $report = [
            'overall_progress' => $this->analyticsService->getUserOverallProgress($user),
            'weak_areas' => $this->analyticsService->getWeakAreas($user),
            'recommendations' => $this->analyticsService->getRecommendations($user),
            'performance_trend' => $this->analyticsService->getPerformanceTrend($user, 30),
            'today_analytics' => $this->analyticsService->recordDailyAnalytics($user),
            'user_stats' => [
                'total_points' => $user->total_points,
                'daily_streak' => $user->daily_streak,
                'level' => $user->level,
                'performance_score' => $user->performance_score,
            ],
        ];

        return response()->json($report);
    }
}
