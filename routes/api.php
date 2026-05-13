<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\LeaderboardController;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);

// Public chatbot routes
Route::get('/chatbot/help', [ChatbotController::class, 'help']);
Route::post('/chatbot/chat', [ChatbotController::class, 'chat']);
Route::get('/chatbot/suggestions', [ChatbotController::class, 'suggestions']);
Route::get('/chatbot/recommendations', [ChatbotController::class, 'recommendations']);
Route::post('/chatbot/search-courses', [ChatbotController::class, 'searchCourses']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Public course listing
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::get('/courses/{courseId}/lessons', [LessonController::class, 'indexByCourse']);
Route::get('/lessons/{id}', [LessonController::class, 'show']);
Route::get('/lessons/{lessonId}/exercises', [ExerciseController::class, 'indexByLesson']);
Route::get('/exercises/{id}', [ExerciseController::class, 'show']);
Route::get('/courses/{courseId}/quizzes', [QuizController::class, 'indexByCourse']);
Route::get('/quizzes/{id}', [QuizController::class, 'show']);

// Public leaderboard route
Route::get('/leaderboard', [LeaderboardController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);

    // Courses
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll']);
    Route::get('/my-enrollments', [CourseController::class, 'myEnrollments']);
    Route::get('/courses/{id}/progress', [CourseController::class, 'getProgress']);

    // Lessons
    Route::post('/lessons', [LessonController::class, 'store']);
    Route::put('/lessons/{id}', [LessonController::class, 'update']);
    Route::post('/lessons/{id}/complete', [LessonController::class, 'markAsCompleted']);
    Route::get('/lessons/{id}/progress', [LessonController::class, 'getProgress']);
    Route::put('/lessons/{id}/progress', [LessonController::class, 'updateProgress']);

    // Exercises
    Route::post('/exercises', [ExerciseController::class, 'store']);
    Route::post('/exercises/{id}/submit', [ExerciseController::class, 'submit']);
    Route::get('/exercises/{id}/submission', [ExerciseController::class, 'getSubmission']);
    Route::get('/exercises/{exerciseId}/submissions', [ExerciseController::class, 'getSubmissions']);
    Route::put('/submissions/{submissionId}/grade', [ExerciseController::class, 'gradeSubmission']);
    Route::get('/exercises/{exerciseId}/stats', [ExerciseController::class, 'getStats']);

    // Quizzes
    Route::post('/quizzes', [QuizController::class, 'store']);
    Route::post('/quizzes/{quizId}/questions', [QuizController::class, 'addQuestion']);
    Route::post('/quizzes/{id}/submit', [QuizController::class, 'submit']);
    Route::get('/quizzes/{id}/results', [QuizController::class, 'getResults']);
    Route::get('/quizzes/{quizId}/stats', [QuizController::class, 'getStats']);

    // Progress & Dashboard
    Route::get('/dashboard', [ProgressController::class, 'getDashboard']);
    Route::get('/courses/{courseId}/progress-details', [ProgressController::class, 'getCourseProgress']);
    Route::get('/lessons/{lessonId}/progress-details', [ProgressController::class, 'getLessonProgress']);
    Route::get('/quizzes/{quizId}/progress', [ProgressController::class, 'getQuizProgress']);
    Route::get('/performance-report', [ProgressController::class, 'getPerformanceReport']);

    // Gamification Routes
    Route::prefix('gamification')->group(function () {
        Route::get('/badges', [GamificationController::class, 'getBadges']);
        Route::get('/badges-with-progress', [GamificationController::class, 'getAllBadgesWithProgress']);
        Route::get('/points-and-stats', [GamificationController::class, 'getPointsAndStats']);
        Route::get('/leaderboard', [GamificationController::class, 'getLeaderboard']);
    });

    // Notifications Routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [GamificationController::class, 'getNotifications']);
        Route::patch('/{notificationId}/read', [GamificationController::class, 'markNotificationAsRead']);
        Route::patch('/read-all', [GamificationController::class, 'markAllNotificationsAsRead']);
        Route::get('/unread-count', [GamificationController::class, 'getUnreadCount']);
    });

    // Analytics Routes
    Route::prefix('analytics')->group(function () {
        Route::get('/overall-progress', [AnalyticsController::class, 'getOverallProgress']);
        Route::get('/course/{courseId}/progress', [AnalyticsController::class, 'getCourseProgress']);
        Route::get('/weak-areas', [AnalyticsController::class, 'getWeakAreas']);
        Route::get('/recommendations', [AnalyticsController::class, 'getRecommendations']);
        Route::get('/daily-analytics', [AnalyticsController::class, 'getDailyAnalytics']);
        Route::get('/analytics-range', [AnalyticsController::class, 'getAnalyticsRange']);
        Route::get('/performance-trend', [AnalyticsController::class, 'getPerformanceTrend']);
        Route::get('/performance-report', [AnalyticsController::class, 'getPerformanceReport']);
    });

    // Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'getDashboardOverview']);
        
        // Users Management
        Route::get('/users', [AdminController::class, 'listUsers']);
        Route::get('/users/{userId}', [AdminController::class, 'getUserDetails']);
        Route::post('/users/{userId}/reset-progress', [AdminController::class, 'resetUserProgress']);

        // Badges Management
        Route::get('/badges', [AdminController::class, 'listBadges']);
        Route::post('/badges', [AdminController::class, 'createBadge']);
        Route::put('/badges/{badgeId}', [AdminController::class, 'updateBadge']);
        Route::delete('/badges/{badgeId}', [AdminController::class, 'deleteBadge']);

        // Statistics & Reports
        Route::get('/stats', [AdminController::class, 'getPlatformStats']);
        Route::post('/reports', [AdminController::class, 'generateReport']);
    });
});

