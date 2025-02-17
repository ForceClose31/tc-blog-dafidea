<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'komentar' => 'required|string',
        ]);

        $comment = Comment::create([
            'komentar' => $request->komentar,
            'tanggal_komentar' => now(),
            'post_id' => $postId,
            'user_id' => Auth::id(),
        ]);

        return response()->json($comment);
    }
}
