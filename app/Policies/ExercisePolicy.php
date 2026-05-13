<?php

namespace App\Policies;

use App\Models\Exercise;
use App\Models\User;
use App\Models\Lesson;

class ExercisePolicy
{
    public function update(User $user, Exercise $exercise)
    {
        $lesson = $exercise->lesson;
        $course = $lesson->course;
        
        return $user->id === $course->instructor_id || $user->isAdmin();
    }

    public function delete(User $user, Exercise $exercise)
    {
        $lesson = $exercise->lesson;
        $course = $lesson->course;
        
        return $user->id === $course->instructor_id || $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isInstructor() || $user->isAdmin();
    }
}
