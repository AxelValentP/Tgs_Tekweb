<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Mengembalikan semua user (tanpa acak).
     * Cocok bila ingin diacak di front-end.
     */
    public function indexAll()
    {
        // Get all users with id, name, and profile_image
        $users = User::get(['id', 'name', 'profile_image']);

        return response()->json($users, 200);
    }


    /**
     * Mengembalikan semua user dalam urutan acak.
     * Cocok bila Anda mau data sudah di-shuffle di server.
     */
    public function indexAllShuffled()
    {
        // Get id, name, and profile_image, then shuffle the results
        $users = User::get(['id', 'name', 'profile_image'])->toArray();
        shuffle($users);

        return response()->json($users, 200);
    }



    /**
     * Mencari user berdasarkan query string.
     * Misal: GET /users/search?query=john
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([], 200);
        }

        // Search users with name matching the query
        $users = User::where('name', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'name', 'profile_image']);

        return response()->json($users, 200);
    }
}
