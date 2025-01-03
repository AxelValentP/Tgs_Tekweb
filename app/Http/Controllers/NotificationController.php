<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\CommentLike; // Ensure this model exists
use App\Models\Reply;       // Ensure this model exists
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Private method to fetch unread notifications.
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchNotifications()
    {
        $userId = Auth::id();

        // Define a timeframe (e.g., last 24 hours)
        $timeFrame = Carbon::now()->subHours(24);

        // Fetch likes on user's posts
        $likes = DB::table('likes')
            ->join('users', 'likes.user_id', '=', 'users.id')
            ->join('posts', 'likes.post_id', '=', 'posts.id')
            ->where('posts.user_id', $userId)
            ->where('likes.seen', 0)
            ->where('likes.created_at', '>=', $timeFrame)
            ->select(
                'likes.id as like_id',
                'users.name as user_name',
                'posts.id as post_id',
                'likes.created_at'
            )
            ->get();

        // Fetch comments on user's posts
        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->join('posts', 'comments.post_id', '=', 'posts.id')
            ->where('posts.user_id', $userId)
            ->where('comments.seen', 0)
            ->where('comments.created_at', '>=', $timeFrame)
            ->select(
                'comments.id as comment_id',
                'users.name as user_name',
                'comments.text as comment_text',
                'posts.id as post_id',
                'comments.created_at'
            )
            ->get();

        // Fetch comment_likes on user's comments
        $commentLikes = DB::table('comment_likes')
            ->join('users', 'comment_likes.user_id', '=', 'users.id')
            ->join('comments', 'comment_likes.comment_id', '=', 'comments.id')
            ->join('posts', 'comments.post_id', '=', 'posts.id')
            ->where('posts.user_id', $userId)
            ->where('comment_likes.seen', 0)
            ->where('comment_likes.created_at', '>=', $timeFrame)
            ->select(
                'comment_likes.id as comment_like_id',
                'users.name as user_name',
                'comments.id as comment_id',
                'comment_likes.created_at'
            )
            ->get();

        // Fetch replies to user's comments
        $replies = DB::table('replies')
            ->join('users', 'replies.user_id', '=', 'users.id')
            ->join('comments', 'replies.comment_id', '=', 'comments.id')
            ->join('posts', 'comments.post_id', '=', 'posts.id')
            ->where('posts.user_id', $userId)
            ->where('replies.seen', 0)
            ->where('replies.created_at', '>=', $timeFrame)
            ->select(
                'replies.id as reply_id',
                'users.name as user_name',
                'replies.text as reply_text',
                'comments.id as comment_id',
                'replies.created_at'
            )
            ->get();

        // Merge all notifications into a single collection
        $notifications = $likes->map(function($item) {
            return (object)[
                'type' => 'like',
                'user_name' => $item->user_name,
                'created_at' => $item->created_at,
                'post_id' => $item->post_id,
            ];
        })->merge($comments->map(function($item) {
            return (object)[
                'type' => 'comment',
                'user_name' => $item->user_name,
                'comment_text' => $item->comment_text,
                'created_at' => $item->created_at,
                'post_id' => $item->post_id,
            ];
        }))->merge($commentLikes->map(function($item) {
            return (object)[
                'type' => 'comment_like',
                'user_name' => $item->user_name,
                'created_at' => $item->created_at,
                'comment_id' => $item->comment_id,
            ];
        }))->merge($replies->map(function($item) {
            return (object)[
                'type' => 'reply',
                'user_name' => $item->user_name,
                'reply_text' => $item->reply_text,
                'created_at' => $item->created_at,
                'comment_id' => $item->comment_id,
            ];
        }));

        // Sort notifications by created_at descending
        $notifications = $notifications->sortByDesc('created_at');

        return $notifications;
    }

    /**
     * Get the total count of unread notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadCount()
    {
        // Hardcode user_id = 1 for testing
        $userId = Auth::id();
        if (!$userId) {
            return response()->json([
                'error' => 'User is not authenticated.',
            ], 401);
        }
    
        // Define the aggregated query using UNION ALL
        $aggregatedQuery = "
            SELECT COUNT(*) AS total_unread_notifications FROM (
                SELECT likes.id
                FROM likes
                JOIN posts ON likes.post_id = posts.id
                WHERE posts.user_id = $userId AND likes.seen = 0
    
                UNION ALL
    
                SELECT comments.id
                FROM comments
                JOIN posts ON comments.post_id = posts.id
                WHERE posts.user_id = $userId  AND comments.seen = 0
    
                UNION ALL
    
                SELECT comment_likes.id
                FROM comment_likes
                JOIN comments ON comment_likes.comment_id = comments.id
                WHERE comments.user_id = $userId  AND comment_likes.seen = 0
    
                UNION ALL
    
                SELECT replies.id
                FROM replies
                JOIN comments ON replies.comment_id = comments.id
                WHERE comments.user_id = $userId  AND replies.seen = 0
            ) AS unread_notifications;
        ";
    
        try {
            // Execute the aggregated query
            $result = DB::select($aggregatedQuery);
    
            // Get the total unread notifications count
            $totalUnreadCount = $result[0]->total_unread_notifications ?? 0;
    
            // Return the count as JSON
            return response()->json([
                'unread_notifications_count' => $totalUnreadCount,
            ]);
    
        } catch (\Exception $e) {
            // Return an error response without using \Log
            return response()->json([
                'error' => 'An error occurred while fetching unread notifications.',
                'message' => $e->getMessage(),
            ], 500);
        }
        
    } 
    

    /**
     * Fetch and display unread notifications.
     *
     * @return \Illuminate\View\View
     */
    public function getNotifications()
    {
        // Fetch notifications using the shared method
        $notifications = $this->fetchNotifications();

        // Pass data to Blade view
        return view('notification', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark all relevant notifications as seen.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearNotifications()
    {
        $userId = Auth::id();

        // Update likes seen to 1 for posts owned by the user
        DB::table('likes')
            ->join('posts', 'likes.post_id', '=', 'posts.id')
            ->where('posts.user_id', $userId)
            ->where('likes.seen', 0)
            ->update(['likes.seen' => 1]);

        // Update comments seen to 1 for posts owned by the user
        DB::table('comments')
            ->join('posts', 'comments.post_id', '=', 'posts.id')
            ->where('posts.user_id', $userId)
            ->where('comments.seen', 0)
            ->update(['comments.seen' => 1]);

        // Update comment_likes seen to 1 for comments on the user's posts
        DB::table('comment_likes')
            ->join('comments', 'comment_likes.comment_id', '=', 'comments.id')
            ->join('posts', 'comments.post_id', '=', 'posts.id')
            ->where('posts.user_id', $userId)
            ->where('comment_likes.seen', 0)
            ->update(['comment_likes.seen' => 1]);

        // Update replies seen to 1 for comments on the user's posts
        DB::table('replies')
            ->join('comments', 'replies.comment_id', '=', 'comments.id')
            ->join('posts', 'comments.post_id', '=', 'posts.id')
            ->where('posts.user_id', $userId)
            ->where('replies.seen', 0)
            ->update(['replies.seen' => 1]);

        return response()->json(['success' => true]);
    }
}
