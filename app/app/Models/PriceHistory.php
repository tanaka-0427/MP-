<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    protected $fillable = ['post_id', 'price', 'recorded_at'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
