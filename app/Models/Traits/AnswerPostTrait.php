<?php

namespace App\Models\Traits;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\User;

/**
 * Trait AnswerPostTrait
 */
trait AnswerPostTrait
{
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
