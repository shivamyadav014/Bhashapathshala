<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'completion_percentage',
        'enrolled_at',
        'completed_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'completion_percentage' => 'float',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Methods
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completion_percentage' => 100,
            'completed_at' => now(),
        ]);
    }

    public function updateProgress($percentage)
    {
        $this->update([
            'completion_percentage' => $percentage,
            'status' => $percentage > 0 ? 'in_progress' : 'enrolled',
        ]);
    }

    public function getProgressSummary()
    {
        return [
            'completion_percentage' => $this->completion_percentage,
            'status' => $this->status,
            'enrolled_since' => $this->enrolled_at->diffForHumans(),
        ];
    }

    /**
     * Recalculate enrollment completion from published lessons the user completed.
     */
    public static function syncProgressFromLessons(User $user, Course $course): ?self
    {
        $lessonIds = $course->lessons()->where('is_published', true)->pluck('id');
        $total = $lessonIds->count();
        if ($total === 0) {
            return static::where('user_id', $user->id)->where('course_id', $course->id)->first();
        }

        $completed = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('is_completed', true)
            ->count();

        $pct = round(($completed / $total) * 100, 2);

        $enrollment = static::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'status' => 'enrolled',
                'enrolled_at' => now(),
            ]
        );

        $enrollment->update([
            'completion_percentage' => $pct,
            'status' => $pct >= 100 ? 'completed' : ($pct > 0 ? 'in_progress' : 'enrolled'),
            'completed_at' => $pct >= 100 ? ($enrollment->completed_at ?? now()) : null,
        ]);

        return $enrollment->fresh();
    }
}
