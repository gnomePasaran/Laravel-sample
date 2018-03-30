<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function commentable()
    {
        return $this->morphTo();
    }
}
