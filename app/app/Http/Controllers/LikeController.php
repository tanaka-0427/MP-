<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function store(Request $request, $postId)
    {
        Like::firstOrCreate([
            'user_id' => auth()->id(),
            'post_id' => $postId
        ]);

        return back()->with('success', 'いいねしました');
    }

    public function destroy($postId)
    {
        Like::where('user_id', auth()->id())
            ->where('post_id', $postId)
            ->delete();

        return back()->with('success', 'いいねを取り消しました');
    }
}
