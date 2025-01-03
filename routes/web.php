<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReplyController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use Illuminate\Notifications\Notification;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

// Home Route (no authentication required)
Route::get('/', function () {
    if (Auth::check()) {
        // Redirect the logged-in user to /explore
        return redirect()->route('explore');
    }
    return view('home');
});

Route::get('/explore', function () {
    return view('explore');
})->name('explore');



Route::get('/load', function () {
    return view('load');
})->name('load');
// Route::get('/notification', function () {
//     return view('notification');
// })->name('notification');

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    // Explore Page
    // Route::get('/explore', [ExploreController::class, 'index'])->name('explore');

    // Home Page - Display User's Posts
    Route::get('/homee', [PostController::class, 'home'])->name('homee');

    // Upload Post Form
    Route::get('/upload', [PostController::class, 'create'])->name('upload');

    // Handle form submission
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    // Like or unlike a post
    Route::post('/posts/{post}/like', [PostController::class, 'likePost'])->name('posts.like');

    // Add a comment to a post
    Route::post('/posts/{post}/comments', [PostController::class, 'addComment'])->name('posts.comments.add');

    // Edit Post Caption via AJAX
    Route::patch('/posts/{post}/edit', [PostController::class, 'editCaption'])->name('posts.editCaption');

    // Delete Post via AJAX
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{postId}/comments', [CommentController::class, 'fetchComments'])->name('comments.fetch');


    //follow unfoll
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow.ajax');

    // Unfollow a user
    Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow.ajax');

    Route::get('/search-users', [ProfileController::class, 'searchUsers'])->name('users.search');
});
//Profile


Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('auth') // Ensure only authenticated users can access
    ->name('profile.show');

Route::post('/profile/update', [ProfileController::class, 'update'])
    ->middleware('auth')
    ->name('profile.update');

// Login & Logout Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Signup Route
    Route::get('/signup', [SignupController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [SignupController::class, 'processSignup'])->name('signup.post');
});

// Logout Route (needs auth)
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/posts', [PostController::class, 'fetchPosts'])->name('posts.fetch');

Route::get('/posts/{id}/details', [PostController::class, 'getDetails']);



// Hidden comment homee
Route::patch('/comments/{id}/hide', [PostController::class, 'hideComment'])->name('comments.hide');
Route::get('/posts/{id}/hidden-comments', [PostController::class, 'getHiddenComments'])->name('posts.hidden-comments');
Route::patch('/comments/{id}/unhide', [PostController::class, 'unhideComment'])->name('comments.unhide');


//Notification
Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
Route::get('/notification', [NotificationController::class, 'getNotifications'])->name('notification');
Route::post('/clear-notifications', [NotificationController::class, 'clearNotifications'])->name('clear.notifications');


//Update status public private
Route::patch('/posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])->name('posts.toggleStatus');
Route::post('/comments/{commentId}/replies', [ReplyController::class, 'store'])->name('replies.store');
Route::get('/comments/{commentId}/replies', [ReplyController::class, 'fetchReplies'])->name('replies.fetch');


Route::middleware(['auth'])->group(function () {
    Route::post('/comments/{commentId}/like', [CommentController::class, 'likeComment'])->name('comments.like');
});



Route::post('/follow/{user}', [FollowController::class, 'follow'])->middleware('auth')->name('follow');
Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->middleware('auth')->name('unfollow');


// Database test route (for checking DB connection)
Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection is OK!';
    } catch (\Exception $e) {
        return 'Database connection failed: ' . $e->getMessage();
    }
});

// Ambil semua topik 
Route::get('/topics/all', [TopicController::class, 'indexAll']);

// Ambil semua topik tapi diacak
Route::get('/topics/all-shuffled', [TopicController::class, 'indexAllShuffled']);

// Search topik
Route::get('/topics/search', [TopicController::class, 'search']);

// Ambil semua user
Route::get('/users/all', [UserController::class, 'indexAll']);

// Ambil semua user acak
Route::get('/users/all-shuffled', [UserController::class, 'indexAllShuffled']);

// Search user
Route::get('/users/search', [UserController::class, 'search']);
