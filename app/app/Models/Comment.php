<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'post_id', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function store(Request $request)
{
    $request->validate([
        'comment' => 'required|string|max:1000',
        'post_id' => 'required|exists:posts,id',
    ]);

    $comment = Comment::create([
        'user_id' => auth()->id(),
        'post_id' => $request->post_id,
        'comment' => $request->comment,
    ]);

    $comment->load('user');

    return response()->json([
        'comment' => $comment,
        'user_name' => $comment->user->name,
        'user_icon' => $comment->user->icon ?? '/default-icon.png', 
    ]);
}

}
