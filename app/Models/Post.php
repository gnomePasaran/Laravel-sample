<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Post extends Model
{
    public function getPublishedPosts()
    {
        return $this->latest('published_at')->published()->get();
    }

    public function scopePublished($query)
    {
        $query->where('published_at', '<=', Carbon::now())
              ->where('published', '=', true);
    }
}
