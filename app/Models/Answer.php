<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Answer extends Model
{
    protected $fillable = ['content']; 

    public function post()
    {
        return $this->belongsTo('Post');
    }
}
