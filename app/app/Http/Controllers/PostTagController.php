<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;

class PostTagController extends Controller
{
    public function attach(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $post->tags()->attach($request->tag_id);
        return back()->with('success', 'タグを追加しました');
    }

    public function detach(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $post->tags()->detach($request->tag_id);
        return back()->with('success', 'タグを削除しました');
    }
}
