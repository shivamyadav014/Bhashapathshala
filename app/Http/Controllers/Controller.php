<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Auth\Access\AuthorizationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Authorize that the user is an instructor
     *
     * @param object $user
     * @return void
     * @throws AuthorizationException
     */
    protected function authorizeIsInstructor($user)
    {
        if (!$user->isInstructor() && !$user->isAdmin()) {
            throw new AuthorizationException('Unauthorized. Only instructors can perform this action.');
        }
    }

    /**
     * Authorize that the user is an admin
     *
     * @param object $user
     * @return void
     * @throws AuthorizationException
     */
    protected function authorizeIsAdmin($user)
    {
        if (!$user->isAdmin()) {
            throw new AuthorizationException('Unauthorized. Only administrators can perform this action.');
        }
    }
}
