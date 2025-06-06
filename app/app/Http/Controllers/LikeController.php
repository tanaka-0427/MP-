<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        $userId = auth()->id();
        $postId = $request->post_id;

        $like = Like::where('user_id', $userId)
                    ->where('post_id', $postId)
                    ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
            $liked = true;
        }

        $likeCount = Like::where('post_id', $postId)->count();

        return response()->json([
            'liked' => $liked,
            'count' => $likeCount
        ]);
    }

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
