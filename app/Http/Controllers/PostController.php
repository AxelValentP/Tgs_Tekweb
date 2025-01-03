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
    // Validate the request data
    $validated = $request->validate([
        'description' => 'required|string|max:255',
        'status' => 'required|in:public,private',
        'topic' => 'nullable|string|max:255',
        'image_links.*' => 'nullable|url',
    ]);

    // Validate image links
    if (!empty($request->image_links)) {
        foreach ($request->image_links as $key => $imageLink) {
            if (!$this->isValidImage($imageLink)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(["image_links.$key" => 'Invalid image link.']);
            }
        }
    }

    // Step 1: Create the post
    $post = Post::create([
        'user_id' => auth()->id(),
        'description' => $request->description,
        'status' => $request->status === 'public' ? 1 : 0,
        'likes_count' => 0,
    ]);

    // Step 2: Handle topics
    $topicIds = [];

    if (!empty($request->topic)) {
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

    // Redirect back with success message
    return redirect()->route('upload')->with('success', 'Post created successfully!');
}

private function isValidImage($url)
{
    try {
        $headers = get_headers($url, 1);

        if (isset($headers['Content-Type'])) {
            $contentType = is_array($headers['Content-Type']) ? $headers['Content-Type'][0] : $headers['Content-Type'];
            return str_starts_with($contentType, 'image/');
        }
    } catch (\Exception $e) {
        return false;
    }

    return false;
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

    public function visibleComments()
    {
        return $this->hasMany(Comment::class)->where('hide', 0);
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

        // Start building the query with the status filter
        $query = Post::where('status', 1) // Only fetch public posts
            ->with(['user', 'images', 'comments.user']);

        // Apply sorting based on the request
        switch ($sort) {
            case 'popular':
                $query->orderBy('likes_count', 'desc');
                break;

            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Implement pagination (fetch 10 posts per page)
        $posts = $query->paginate(10);
        $posts->getCollection()->transform(function ($post) {
            $post->liked = $post->likes->contains('user_id', auth()->id());
            return $post;
        });
        return response()->json($posts);
    }


    public function getDetails($id)
    {
        $post = Post::findOrFail($id);

        // Query directly from the database
        $comments = Comment::where('post_id', $id)
            ->where('hide', 0)
            ->with('user')
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'user' => $comment->user->name,
                    'text' => $comment->text,
                ];
            });

        $likes = $post->likes()->with('user')->get()->map(function ($like) {
            return [
                'user' => $like->user->name,
            ];
        });

        return response()->json([
            'comments' => $comments,
            'likes' => $likes,
        ]);
    }

    public function getHiddenComments($id)
    {
        $hiddenComments = Comment::where('post_id', $id)
            ->where('hide', 1)
            ->with('user')
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'user' => $comment->user->name,
                    'text' => $comment->text,
                ];
            });

        return response()->json([
            'hiddenComments' => $hiddenComments,
        ]);
    }

    public function hideComment($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            $comment->hide = 1;
            $comment->save();

            // Ambil komentar tersembunyi untuk memperbarui tabel
            $hiddenComments = Comment::where('post_id', $comment->post_id)
                ->where('hide', 1)
                ->with('user')
                ->get()
                ->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'user' => $comment->user->name,
                        'text' => $comment->text,
                    ];
                });

            return response()->json([
                'message' => 'Comment hidden successfully.',
                'hiddenComments' => $hiddenComments,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to hide comment.'], 500);
        }
    }



    public function unhideComment($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            $comment->hide = 0;
            $comment->save();

            // Ambil komentar yang tidak tersembunyi langsung dari database
            $comments = Comment::where('post_id', $comment->post_id)
                ->where('hide', 0)
                ->with('user')
                ->get()
                ->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'user' => $comment->user->name,
                        'text' => $comment->text,
                    ];
                });

            return response()->json([
                'message' => 'Comment successfully unhidden.',
                'comments' => $comments,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to unhide comment.'], 500);
        }
    }


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

    public function toggleStatus(Post $post, Request $request)
    {
        // Toggle status
        $post->status = $post->status === 1 ? 0 : 1;
        $post->save();

        // Return response
        return response()->json([
            'status' => $post->status,
            'message' => 'Post status updated successfully!',
        ]);
    }
}
