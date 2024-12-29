<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Fetch all comments for a specific post.
     */
    public function fetchComments($postId)
    {
        $comments = Comment::where('post_id', $postId)
            ->where('hide', false)
            ->with('user') // Include user details
            ->latest() // Order by newest comments first
            ->get();

        return response()->json($comments);
    }

    /**
     * Add a new comment to a specific post.
     */
    public function store(Request $request, $postId)
    {
        // Validate the request
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        // Save the comment
        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => auth()->id(), // Ensure the user is authenticated
            'text' => $request->text,
        ]);

        // Return the newly created comment with the user's details
        return response()->json($comment->load('user'));
    }
}
