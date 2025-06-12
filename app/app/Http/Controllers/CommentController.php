<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'comment' => 'required|string|max:1000',
    ]);

    $comment = Comment::create([
        'user_id' => auth()->id(),
        'post_id' => $request->post_id,
        'comment' => $request->comment,
    ]);

     return response()->json([
        'id' => $comment->id, 
        'user' => $comment->user->name,
        'comment' => $comment->comment,
    ]);
}

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== auth()->id()) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }
    public function update(Request $request, $id)
{
    $comment = Comment::findOrFail($id);

    if ($comment->user_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $request->validate([
        'comment' => 'required|string|max:1000',
    ]);

    $comment->comment = $request->comment;
    $comment->save();

    return response()->json([
        'success' => true,
        'comment' => $comment->comment
    ]);
}

}
