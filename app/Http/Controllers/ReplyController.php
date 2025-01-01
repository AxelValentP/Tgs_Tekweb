<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reply;

class ReplyController extends Controller
{
    public function store(Request $request, $commentId)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $reply = Reply::create([
            'comment_id' => $commentId,
            'user_id' => auth()->id(),
            'text' => $request->text,
        ]);

        return response()->json($reply->load('user'));
    }

    public function fetchReplies($commentId)
    {
        $replies = Reply::where('comment_id', $commentId)->with('user')->latest()->get();
        return response()->json($replies);
    }
}