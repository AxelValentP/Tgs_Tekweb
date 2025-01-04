<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;

class TopicController extends Controller
{
    /**
     * Mengembalikan semua topik (tanpa diacak).
     * Cocok jika ingin mengacak di sisi front-end.
     */
    public function indexAll()
    {
        // Mengambil semua nama topik
        $topics = Topic::pluck('name');

        // Return JSON
        return response()->json($topics, 200);
    }

    /**
     * Mengembalikan semua topik yang sudah diacak (shuffle).
     * Cocok jika Anda ingin data acak langsung dari server.
     */
    public function indexAllShuffled()
    {
        // Ambil semua nama topik sebagai array
        $topics = Topic::pluck('name')->toArray();

        // Acak urutannya
        shuffle($topics);

        // Return JSON
        return response()->json($topics, 200);
    }

    /**
     * Search for topics based on a query string.
     * (Contoh: GET /topics/search?query=lar)
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
}
