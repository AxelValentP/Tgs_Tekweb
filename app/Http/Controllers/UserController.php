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
        // Ambil semua nama user
        $users = User::pluck('name');
        // atau ->get(['id', 'name']) jika Anda mau data lebih lengkap

        return response()->json($users, 200);
    }

    /**
     * Mengembalikan semua user dalam urutan acak.
     * Cocok bila Anda mau data sudah di-shuffle di server.
     */
    public function indexAllShuffled()
    {
        $users = User::pluck('name')->toArray(); // Ambil kolom 'name' lalu jadikan array
        shuffle($users); // Acak urutan array

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

        // Cari user dengan nama yang mengandung query (case-insensitive)
        // Batasi misal 10 hasil
        $users = User::where('name', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($users, 200);
    }
}
