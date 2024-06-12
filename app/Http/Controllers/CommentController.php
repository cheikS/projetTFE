<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'video_id' => 'required|exists:videos,id',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'video_id' => $request->video_id,
            'content' => $request->content,
        ]);

        return response()->json($comment); // Retournez le commentaire créé
    }
}
