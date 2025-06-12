<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PriceHistory;
class Post extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'title', 'content', 'image',
        'price_original', 'price_purchased', 'price_current',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }
    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class);
    }
}

