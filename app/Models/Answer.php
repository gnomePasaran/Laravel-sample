<?php

namespace App\Models;

use App\Mail\PostNotifier;
use App\Models\Post;
use App\Models\Traits\AnswerPostTrait;
use App\Models\Traits\VotableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

/**
 * App\Models\Answer
 *
 * @property int $id
 * @property int $post_id
 * @property string $content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $user_id
 * @property int $is_best
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read \App\Models\Post $post
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vote[] $votes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Answer whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Answer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Answer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Answer whereIsBest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Answer wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Answer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Answer whereUserId($value)
 * @mixin \Eloquent
 */
class Answer extends Model
{
    use AnswerPostTrait, VotableTrait;

    protected $fillable = ['content', 'user_id'];

    /**
     * Boot method.
     */
    protected static function boot()
    {
        static::created(function($model) {
            foreach ($model->post->subscribers() as $subscriber) {
                Mail::to($subscriber)->send(new PostNotifier($model->post));
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Toggle best answer method.
     */
    public function toggleBest(): void
    {
        $this->is_best = ! $this->is_best;

        if ($this->is_best) {
            Answer::query()
                ->where('post_id', '=', $this->post_id)
                ->where('is_best', '=', true)
                ->update(['is_best' => false]);
        }

        $this->save();
    }
}
