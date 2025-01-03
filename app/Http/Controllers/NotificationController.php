<?php
namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;
use App\Models\Topic;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationController extends Controller{
 public function getUnreadCount()
 {
     $userId = Auth::id();
 
     // Ambil ID dari semua postingan milik user
     $userPosts = Post::where('user_id', $userId)->pluck('id');
 
     // Hitung jumlah komentar yang belum dilihat
     $unreadCommentsCount = Comment::whereIn('post_id', $userPosts)
         ->where('seen', 0)
         ->count();
 
     // Hitung jumlah likes yang belum dilihat
     $unreadLikesCount = Like::whereIn('post_id', $userPosts)
         ->where('seen', 0)
         ->count();
 
     // Totalkan jumlah notifikasi
     $totalUnreadCount = $unreadCommentsCount + $unreadLikesCount;
 
     return response()->json([
         'unread_notifications_count' => $totalUnreadCount,
     ]);
 }


 public function getNotifications()
 {
     // User ID dari pengguna yang sedang login
     $userId = Auth::id();
 
     // Define a timeframe (e.g., last 24 hours)
     $timeFrame = Carbon::now()->subHours(24);
 
     // Fetch likes untuk postingan user yang sedang login
     $likes = DB::table('likes')
         ->join('users', 'likes.user_id', '=', 'users.id')
         ->join('posts', 'likes.post_id', '=', 'posts.id')
         ->where('posts.user_id', $userId) 
         ->where('likes.seen', 0)
         ->where('likes.created_at', '>=', $timeFrame)
         ->select('likes.id as like_id', 'users.name as user_name', 'posts.id as post_id', 'likes.created_at')
         ->get();
 
     // Fetch comments untuk postingan user yang sedang login
     $comments = DB::table('comments')
         ->join('users', 'comments.user_id', '=', 'users.id')
         ->join('posts', 'comments.post_id', '=', 'posts.id')
         ->where('posts.user_id', $userId) 
         ->where('comments.seen', 0)
         ->where('comments.created_at', '>=', $timeFrame)
         ->select('comments.id as comment_id', 'users.name as user_name', 'comments.text as comment_text', 'posts.id as post_id', 'comments.created_at')
         ->get();
 
 
     // Pass data ke Blade view
     return view('notification', [
         'likes' => $likes,
         'comments' => $comments,
     ]);
 }
 
 
 
 public function clearNotifications()
 {
     $userId = Auth::id();
 
     // Update likes seen to 1 for posts owned by the user
     DB::table('likes')
         ->join('posts', 'likes.post_id', '=', 'posts.id')
         ->where('posts.user_id', $userId)
         ->where('likes.seen', 0)
         ->update(['seen' => 1]);
 
     // Update comments seen to 1 for posts owned by the user
     DB::table('comments')
         ->join('posts', 'comments.post_id', '=', 'posts.id')
         ->where('posts.user_id', $userId)
         ->where('comments.seen', 0)
         ->update(['seen' => 1]);
 
     return response()->json(['success' => true]);
 }
 

}
?>