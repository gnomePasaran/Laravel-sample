<?php

namespace App\Models\Traits;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations;

/**
 * Trait AnswerPostTrait
 */
trait AnswerPostTrait
{
    /**
     * @return Relations\MorphMany
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * @return Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * @return Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sync attachments with related entity.
     *
     * @param array $attachID
     *
     * @return self
     */
    public function syncAttachment(array $attachID): self
    {
        $this->attachments()->each(function (Attachment $attach) use ($attachID) {
            if (! in_array($attach->id, $attachID)) {
                $attach->delete();
            }
        });

        return $this;
    }
}
