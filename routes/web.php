<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\CommentController;

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
});

// Login & Logout Routes
Route::middleware('guest')->group(function() {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Signup Route
    Route::get('/signup', [SignupController::class, 'showSignup'])->name('signup'); 
    Route::post('/signup', [SignupController::class, 'processSignup'])->name('signup.post');
});

// Logout Route (needs auth)
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/posts', [PostController::class, 'fetchPosts'])->name('posts.fetch');

// Database test route (for checking DB connection)
Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection is OK!';
    } catch (\Exception $e) {
        return 'Database connection failed: ' . $e->getMessage();
    }
});
