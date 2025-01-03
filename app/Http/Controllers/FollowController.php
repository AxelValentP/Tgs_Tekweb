<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    /**
     * Follow a user (via AJAX).
     */
    public function follow(User $user)
    {
        $currentUserId = Auth::id();

        // Cannot follow yourself
        if ($currentUserId === $user->id) {
            return response()->json([
                'error' => 'You cannot follow yourself.'
            ], 422);
        }

        // Check if already followed
        $exists = DB::table('user_followers')
            ->where('user_id', $user->id)
            ->where('follower_id', $currentUserId)
            ->exists();

        if (! $exists) {
            DB::table('user_followers')->insert([
                'user_id' => $user->id,
                'follower_id' => $currentUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Return JSON to let front-end know it's now followed
        return response()->json([
            'message' => "You are now following {$user->name}",
            'followed' => true
        ], 200);
    }

    /**
     * Unfollow a user (via AJAX).
     */
    public function unfollow(User $user)
    {
        $currentUserId = Auth::id();

        DB::table('user_followers')
            ->where('user_id', $user->id)
            ->where('follower_id', $currentUserId)
            ->delete();

        // Return JSON to let front-end know it's now unfollowed
        return response()->json([
            'message' => "You have unfollowed {$user->name}",
            'followed' => false
        ], 200);
    }
}
