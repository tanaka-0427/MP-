<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'profile', 'is_admin','icon',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function favoritePosts()
    {
    return $this->belongsToMany(Post::class, 'favorites');
    }
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function mypage()
{
    $user = auth()->user();
    return view('users.mypage', compact('user'));
}

//レコメンド
public function recommendedPosts()
{
    // お気に入り投稿のカテゴリIDを集計し、多い順に並べる
    $favoriteCategoryIds = $this->favoritePosts()
        ->select('category_id')
        ->groupBy('category_id')
        ->orderByRaw('COUNT(*) DESC')
        ->pluck('category_id');

    if ($favoriteCategoryIds->isEmpty()) {
        // お気に入りがない場合はランダムに投稿を返す
        return Post::inRandomOrder()->limit(12)->get();
    }

    $topCategoryId = $favoriteCategoryIds->first();

    // 上位カテゴリの投稿から、まだお気に入りしていない投稿を取得
    return Post::where('category_id', $topCategoryId)
        ->whereNotIn('id', $this->favoritePosts()->pluck('posts.id'))
        ->limit(12)
        ->get();
}

}

