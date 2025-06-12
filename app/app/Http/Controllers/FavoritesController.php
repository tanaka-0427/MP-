<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Post;
use App\Models\PriceHistory;
class FavoritesController extends Controller
{
    // お気に入り登録
    public function store(Request $request, $postId)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'post_id' => $postId
        ]);

        return back()->with('success', 'お気に入りに追加しました');
    }

    // お気に入り解除
    public function destroy($postId)
    {
        Favorite::where('user_id', auth()->id())
            ->where('post_id', $postId)
            ->delete();

        return back()->with('success', 'お気に入りを解除しました');
    }

    // お気に入り一覧表示
    public function index()
    {
        $user = auth()->user();

        $favorites = $user->favorites()
                  ->with(['post.category', 'post.user', 'post.priceHistories']) 
                  ->latest()
                  ->paginate(10);

        return view('favorites.index', compact('favorites'));
    }
}
