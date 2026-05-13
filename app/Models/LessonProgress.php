<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'is_completed',
        'progress_percentage',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'float',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // Methods
    public function markAsCompleted()
    {
        $this->update([
            'is_completed' => true,
            'progress_percentage' => 100,
            'completed_at' => now(),
        ]);
    }

    public function updateProgress($percentage)
    {
        $this->update([
            'progress_percentage' => $percentage,
        ]);

        if ($percentage >= 100) {
            $this->markAsCompleted();
        }
    }

    public function getTimeSpent()
    {
        return $this->completed_at 
            ? $this->completed_at->diffInMinutes($this->started_at) 
            : now()->diffInMinutes($this->started_at);
    }
}
