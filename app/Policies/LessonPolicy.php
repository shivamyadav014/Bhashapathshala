<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function update(User $user, Lesson $lesson)
    {
        $course = $lesson->course;
        
        return $user->id === $course->instructor_id || $user->isAdmin();
    }

    public function delete(User $user, Lesson $lesson)
    {
        $course = $lesson->course;
        
        return $user->id === $course->instructor_id || $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isInstructor() || $user->isAdmin();
    }
}
