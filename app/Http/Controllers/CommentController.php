<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentLike;
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
        ->withCount('likes') // Include the likes count
        ->get()
        ->map(function ($comment) {
            return [
                'id' => $comment->id,
                'user' => $comment->user,
                'text' => $comment->text,
                'created_at' => $comment->created_at,
                'likes_count' => $comment->likes_count,
                'liked' => $comment->likes->contains('user_id', auth()->id()),
            ];
        });

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
    public function likeComment(Request $request, $commentId)
    {
        $userId = auth()->id();
        $comment = Comment::findOrFail($commentId);

        $existingLike = CommentLike::where('comment_id', $commentId)->where('user_id', $userId)->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['message' => 'Comment unliked.', 'likes_count' => $comment->likes()->count()]);
        }

        CommentLike::create(['comment_id' => $commentId, 'user_id' => $userId]);
        return response()->json(['message' => 'Comment liked.', 'likes_count' => $comment->likes()->count()]);
    }
}
