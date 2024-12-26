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

class PostController extends Controller
{
    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        // Fetch latest posts with relations for display (optional)
        $posts = Post::with(['user', 'topics', 'images', 'likes', 'comments.user'])->latest()->get();
        return view('upload', compact('posts'));
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(StorePostRequest $request)
    {
        // Step 1: Create the post
        $post = Post::create([
            'user_id' => auth()->id(),
            'description' => $request->description,
            'likes_count' => 0, // Initialize likes_count
        ]);

        // Step 2: Handle topics
        $topicIds = [];

        if (!empty($request->topic)) {
            // Assuming only one topic based on your form input
            $topicName = trim($request->topic);
            if ($topicName !== '') {
                $topic = Topic::firstOrCreate(
                    ['name' => $topicName],
                    ['slug' => Str::slug($topicName)]
                );
                $topicIds[] = $topic->id;
            }
        }

        // Attach topics to the post
        if (!empty($topicIds)) {
            $post->topics()->sync($topicIds);
        }

        // Step 3: Handle images
        if (!empty($request->image_links)) {
            foreach ($request->image_links as $imageLink) {
                Image::create([
                    'post_id' => $post->id,
                    'path' => $imageLink,
                ]);
            }
        }

        return redirect()->route('upload')->with('success', 'Post created successfully!');
    }

    /**
     * Like or unlike a post.
     */
    public function likePost(Request $request, $postId)
    {
        $user = auth()->user();

        $post = Post::findOrFail($postId);

        $existingLike = $post->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            // Unlike the post
            $existingLike->delete();
            $post->decrement('likes_count');
            return response()->json(['message' => 'Post unliked successfully.', 'likes_count' => $post->likes_count]);
        }

        // Like the post
        $post->likes()->create(['user_id' => $user->id]);
        $post->increment('likes_count');
        return response()->json(['message' => 'Post liked successfully.', 'likes_count' => $post->likes_count]);
    }

    /**
     * Add a comment to a post.
     */
    public function addComment(StoreCommentRequest $request, $postId)
    {
        $post = Post::findOrFail($postId);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'text' => $request->text,
        ]);

        return response()->json(['message' => 'Comment added successfully.']);
    }

    public function editCaption(Request $request, Post $post)
    {
        // Ensure the authenticated user owns the post
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate the request
        $validated = $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        // Update the post's description and mark as edited
        $post->description = $validated['description'];
        $post->edited = true;
        $post->save();

        return response()->json(['message' => 'Post updated successfully!', 'description' => $post->description]);
    }

    /**
     * Delete a post.
     */
    public function destroy(Post $post)
    {
        // Ensure the authenticated user owns the post
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete related images first due to foreign key constraints
        $post->images()->delete();

        // Detach topics
        $post->topics()->detach();

        // Delete the post
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully!']);
    }

    public function home()
    {
        // Fetch posts belonging to the authenticated user, with related topics and images
        $posts = Post::where('user_id', Auth::id())
            ->with(['topics', 'images'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('homee', compact('posts'));
    }

    public function fetchPosts(Request $request)
    {
        $sort = $request->get('sort', 'newest'); // Default to 'newest' if not provided

        switch ($sort) {
            case 'newest':
                $posts = Post::with(['user', 'images', 'comments.user'])
                    ->orderBy('created_at', 'desc');
                break;

            case 'popular':
                $posts = Post::with(['user', 'images', 'comments.user'])
                    ->orderBy('likes_count', 'desc');
                break;

            case 'oldest':
                $posts = Post::with(['user', 'images', 'comments.user'])
                    ->orderBy('created_at', 'asc');
                break;

            default:
                $posts = Post::with(['user', 'images', 'comments.user'])
                    ->orderBy('created_at', 'desc');
                break;
        }

        // Implement pagination (optional but recommended)
        $posts = $posts->paginate(10); // Fetch 10 posts per page

        return response()->json($posts);
    }

    public function getDetails($id)
    {
        $post = Post::with(['comments.user', 'likes.user'])->findOrFail($id);
    
        return response()->json([
            'comments' => $post->comments->where('hidden', 0)->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'user' => $comment->user->name,
                    'text' => $comment->text,
                ];
            }),
            'likes' => $post->likes->map(function ($like) {
                return [
                    'user' => $like->user->name,
                ];
            }),
        ]);
    }

    public function hideComment(Request $request, $id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->hidden = 1;
            $comment->save();
    
            return response()->json(['message' => 'Comment hidden successfully']);
        }
    
        return response()->json(['message' => 'Comment not found'], 404);
    }
}
