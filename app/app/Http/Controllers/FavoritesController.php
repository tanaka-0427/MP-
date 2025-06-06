<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Post;

class FavoritesController extends Controller
{
    public function store(Request $request, $postId)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'post_id' => $postId
        ]);

        return back()->with('success', 'お気に入りに追加しました');
    }

    public function destroy($postId)
    {
        Favorite::where('user_id', auth()->id())
            ->where('post_id', $postId)
            ->delete();

        return back()->with('success', 'お気に入りを解除しました');
    }
}
