<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // For Query Builder
use App\Models\User; // If you want to use Eloquent for the "other users" query

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Total posts by the user (assuming a Post model with user_id column)
        $totalPosts = DB::table('posts')->where('user_id', $user->id)->count();

        // Total followers
        $totalFollowers = DB::table('user_followers')->where('user_id', $user->id)->count();

        // Total following
        $totalFollowing = DB::table('user_followers')->where('follower_id', $user->id)->count();

        // --- NEW CODE: Query for "all other users" ---
        // Option A: Using Query Builder
        // $otherUsers = DB::table('users')
        //     ->where('id', '!=', $user->id)
        //     ->get();

        // Option B: Using Eloquent (recommended, if your 'User' model is set up):
        $otherUsers = User::where('id', '!=', $user->id)->get();

        return view('profile', [
            'user'           => $user,
            'totalPosts'     => $totalPosts,
            'totalFollowers' => $totalFollowers,
            'totalFollowing' => $totalFollowing,
            'otherUsers'     => $otherUsers, // Pass to Blade
        ]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login')->with('error', 'You need to log in to update your profile.');
        }

        // Validate incoming data with unique check for the name
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $userId,
            'description' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|url', // Ensure it's a valid URL
        ]);

        // Update user attributes directly in the database
        try {
            DB::table('users')->where('id', $userId)->update([
                'name'          => $request->input('name'),
                'description'   => $request->input('description'),
                'profile_image' => $request->input('profile_image'),
            ]);

            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('An error occurred while updating your profile. Please try again.');
        }
    }
    public function searchUsers(Request $request)
{
    $query = $request->input('q', '');
    $filter = $request->input('filter', 'all'); 
    $authId = auth()->id();

    // Base query: all users except self (maybe?)
    $usersQuery = User::where('id', '!=', $authId);

    // If $query is not empty, filter by name
    if (!empty($query)) {
       $usersQuery->where('name', 'like', '%'.$query.'%');
    }

    $allUsers = $usersQuery->get();

    // Check if followed (by me)
    // We'll attach 'is_followed' => bool
    // Also filter out if needed
    $results = [];
    foreach ($allUsers as $u) {
        $isFollowed = DB::table('user_followers')
            ->where('user_id', $u->id)
            ->where('follower_id', $authId)
            ->exists();

        // Based on $filter, decide if we skip
        if ($filter === 'followed' && !$isFollowed) {
            continue;
        } 
        if ($filter === 'unfollowed' && $isFollowed) {
            continue;
        }

        $results[] = [
            'id' => $u->id,
            'name' => $u->name,
            'profile_image' => $u->profile_image,
            'is_followed' => $isFollowed,
        ];
    }

    return response()->json($results, 200);
}

}
