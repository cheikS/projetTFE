<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'video_id' => 'required|integer|exists:videos,id',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'video_id' => $request->video_id,
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        if ($request->wantsJson() || $request->inertia()) {
            return back()->with('success', 'Comment submitted successfully!');
        }

        return response()->json($comment);
    }


    public function destroy($id)
    {
        // Trouver le commentaire par ID
        $comment = Comment::findOrFail($id);

        // Vérifier si l'utilisateur connecté est l'auteur du commentaire
        if (auth()->user()->id !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Supprimer le commentaire
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
