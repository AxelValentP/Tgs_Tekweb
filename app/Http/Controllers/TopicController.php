<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\User;

class TopicController extends Controller
{
    /**
     * Search for topics based on a query string.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([], 200);
        }

        // Search for topics that contain the query string, case-insensitive
        $topics = Topic::where('name', 'LIKE', '%' . $query . '%')
            ->limit(10) // Limit to 10 suggestions
            ->get(['id', 'name']);

        return response()->json($topics, 200);
    }

    /**
     * Retrieve topics with optional search and pagination.
     */
    public function getTopics(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Topic::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 3);
        $offset = ($page - 1) * $limit;

        $total = $query->count();
        $topics = $query->orderBy('name', 'asc')->skip($offset)->take($limit)->get();

        $last_page = ceil($total / $limit);

        return response()->json([
            'data' => $topics,
            'current_page' => $page,
            'last_page' => $last_page,
            'message' => $topics->isEmpty() ? 'No topics found.' : null,
        ]);
    }

    /**
     * Retrieve users with optional search and pagination.
     */
    public function getUsers(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $query = User::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 3);
        $offset = ($page - 1) * $limit;

        $total = $query->count();
        $users = $query->orderBy('name', 'asc')->skip($offset)->take($limit)->get();

        $last_page = ceil($total / $limit);

        return response()->json([
            'data' => $users,
            'current_page' => $page,
            'last_page' => $last_page,
            'message' => $users->isEmpty() ? 'No users found.' : null,
        ]);
    }
}
