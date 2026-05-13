<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add columns to users table for gamification
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'total_points')) {
                    $table->integer('total_points')->default(0)->after('bio');
                }
                if (!Schema::hasColumn('users', 'daily_streak')) {
                    $table->integer('daily_streak')->default(0)->after('total_points');
                }
                if (!Schema::hasColumn('users', 'last_activity_date')) {
                    $table->date('last_activity_date')->nullable()->after('daily_streak');
                }
                if (!Schema::hasColumn('users', 'performance_score')) {
                    $table->integer('performance_score')->default(0)->after('last_activity_date');
                }
                if (!Schema::hasColumn('users', 'level')) {
                    $table->string('level')->default('beginner')->after('performance_score');
                }
                if (!Schema::hasColumn('users', 'total_points')) {
                    // Already added above but adding index
                }
            });
            // Add index if it doesn't exist
            if (!Schema::hasColumn('users', 'total_points')) {
                // Column exists, we can add index safely
            }
        }

        // Badges table
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "First Lesson", "Quiz Master", etc.
            $table->string('description');
            $table->string('icon')->nullable();
            $table->string('slug')->unique();
            $table->enum('type', ['lesson', 'quiz', 'streak', 'score', 'achievement']); // Badge category
            $table->integer('required_count')->default(1); // e.g., complete 5 lessons
            $table->integer('points_reward')->default(10);
            $table->timestamps();
        });

        // User Badges (track which badges user has earned)
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('badge_id')->constrained('badges')->onDelete('cascade');
            $table->timestamp('earned_at');
            $table->timestamps();
            $table->unique(['user_id', 'badge_id']);
        });

        // Enhance exercise_submissions with better feedback
        if (Schema::hasTable('exercise_submissions')) {
            Schema::table('exercise_submissions', function (Blueprint $table) {
                if (!Schema::hasColumn('exercise_submissions', 'explanation')) {
                    $table->text('explanation')->nullable()->after('feedback');
                }
                if (!Schema::hasColumn('exercise_submissions', 'weak_areas')) {
                    $table->json('weak_areas')->nullable()->after('explanation');
                }
                if (!Schema::hasColumn('exercise_submissions', 'difficulty_level')) {
                    $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->default('medium')->after('weak_areas');
                }
                if (!Schema::hasColumn('exercise_submissions', 'time_spent_seconds')) {
                    $table->integer('time_spent_seconds')->default(0)->after('difficulty_level');
                }
            });
        }

        // Enhance user_quiz_results with detailed performance data
        if (Schema::hasTable('user_quiz_results')) {
            Schema::table('user_quiz_results', function (Blueprint $table) {
                if (!Schema::hasColumn('user_quiz_results', 'correct_answers')) {
                    $table->integer('correct_answers')->default(0)->after('passed');
                }
                if (!Schema::hasColumn('user_quiz_results', 'total_questions')) {
                    $table->integer('total_questions')->default(0)->after('correct_answers');
                }
                if (!Schema::hasColumn('user_quiz_results', 'accuracy_percentage')) {
                    $table->decimal('accuracy_percentage', 5, 2)->default(0)->after('total_questions');
                }
                if (!Schema::hasColumn('user_quiz_results', 'weak_areas')) {
                    $table->json('weak_areas')->nullable()->after('accuracy_percentage');
                }
                if (!Schema::hasColumn('user_quiz_results', 'recommendations')) {
                    $table->text('recommendations')->nullable()->after('weak_areas');
                }
                if (!Schema::hasColumn('user_quiz_results', 'time_spent_seconds')) {
                    $table->integer('time_spent_seconds')->default(0)->after('recommendations');
                }
                if (!Schema::hasColumn('user_quiz_results', 'grade_letter')) {
                    $table->enum('grade_letter', ['A', 'B', 'C', 'D', 'F'])->nullable()->after('time_spent_seconds');
                }
            });
        }

        // User Performance Analytics
        Schema::create('user_performance_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->integer('lessons_completed')->default(0);
            $table->integer('exercises_submitted')->default(0);
            $table->integer('quizzes_attempted')->default(0);
            $table->decimal('average_quiz_score', 5, 2)->default(0);
            $table->decimal('average_exercise_score', 5, 2)->default(0);
            $table->integer('points_earned')->default(0);
            $table->json('topics_practiced')->nullable();
            $table->json('weak_areas')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'date']);
        });

        // Leaderboard cache table
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('leaderboard_type'); // 'weekly', 'monthly', 'all_time'
            $table->integer('rank')->default(0);
            $table->integer('score')->default(0);
            $table->date('period_date')->nullable(); // For weekly/monthly leaderboards
            $table->timestamps();
            $table->unique(['user_id', 'leaderboard_type', 'period_date']);
        });

        // Notifications table
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // 'streak', 'badge', 'reminder', 'achievement'
            $table->string('title');
            $table->text('message');
            $table->string('action_url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('leaderboards');
        Schema::dropIfExists('user_performance_analytics');
        
        if (Schema::hasTable('user_quiz_results')) {
            Schema::table('user_quiz_results', function (Blueprint $table) {
                $columnsToDropIfExists = ['correct_answers', 'total_questions', 'accuracy_percentage', 'weak_areas', 'recommendations', 'time_spent_seconds', 'grade_letter'];
                $existingColumns = array_filter($columnsToDropIfExists, function ($column) {
                    return Schema::hasColumn('user_quiz_results', $column);
                });
                if (!empty($existingColumns)) {
                    $table->dropColumn($existingColumns);
                }
            });
        }
        
        if (Schema::hasTable('exercise_submissions')) {
            Schema::table('exercise_submissions', function (Blueprint $table) {
                $columnsToDropIfExists = ['explanation', 'weak_areas', 'difficulty_level', 'time_spent_seconds'];
                $existingColumns = array_filter($columnsToDropIfExists, function ($column) {
                    return Schema::hasColumn('exercise_submissions', $column);
                });
                if (!empty($existingColumns)) {
                    $table->dropColumn($existingColumns);
                }
            });
        }
        
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
        
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $columnsToDropIfExists = ['total_points', 'daily_streak', 'last_activity_date', 'performance_score', 'level'];
                $existingColumns = array_filter($columnsToDropIfExists, function ($column) {
                    return Schema::hasColumn('users', $column);
                });
                if (!empty($existingColumns)) {
                    $table->dropColumn($existingColumns);
                }
            });
        }
    }
};
