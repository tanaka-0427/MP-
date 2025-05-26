<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostTag extends Pivot
{
    protected $table = 'post_tags';
    protected $fillable = ['post_id', 'tag_id'];
}
