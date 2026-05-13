<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    /**
     * Display the leaderboard (top users by total points)
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 20);
        $users = User::orderByDesc('total_points')
            ->select('id', 'name', 'total_points', 'performance_score', 'level', 'profile_image')
            ->take($limit)
            ->get();

        return response()->json([
            'leaderboard' => $users
        ]);
    }
}
