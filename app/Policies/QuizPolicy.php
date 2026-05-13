<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    public function update(User $user, Quiz $quiz)
    {
        $course = $quiz->course;
        
        return $user->id === $course->instructor_id || $user->isAdmin();
    }

    public function delete(User $user, Quiz $quiz)
    {
        $course = $quiz->course;
        
        return $user->id === $course->instructor_id || $user->isAdmin();
    }

    public function addQuestion(User $user, Quiz $quiz)
    {
        $course = $quiz->course;
        
        return $user->id === $course->instructor_id || $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isInstructor() || $user->isAdmin();
    }
}
