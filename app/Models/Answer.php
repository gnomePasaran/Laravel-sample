<?php

namespace App\Models;

use App\Mail\PostNotifier;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Vote;
use App\Models\User;
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
    /** @var array  */
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable' );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->votes->sum('score');
    }

    /**
     * @param \App\Models\User $user
     */
    public function voteUp(User $user)
    {
        $vote = $this->votes()->firstOrNew(['user_id' => $user->id]);
        $vote->score = 1;
        $vote->save();
    }

    /**
     * @param \App\Models\User $user
     */
    public function voteDown(User $user)
    {
        $vote = $this->votes()->firstOrNew(['user_id' => $user->id]);
        $vote->score = -1;
        $vote->save();
    }

    /**
     * @param \App\Models\User $user
     */
    public function voteCancel(User $user)
    {
        if ($vote = $this->votes()->where(['user_id' => $user->id]))
            $vote->delete();
    }
}
