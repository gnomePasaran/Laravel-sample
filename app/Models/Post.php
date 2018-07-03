<?php

namespace App\Models;

use App\Models\Answer;
use App\Models\Comment;
use App\Models\Subscription;
use App\Models\Vote;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property string|null $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $published_at
 * @property int $published
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Answer[] $answers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Subscription[] $subscriptions
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vote[] $votes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post fullPostRelatives()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post published()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereUserId($value)
 * @mixin \Eloquent
 */
class Post extends Model
{
    /** @var array  */
    protected $fillable = ['title', 'content', 'published', 'user_id'];

    /**
     * Boot method
     */
    protected static function boot()
    {
        static::saving(function($model) {
            $model->slug = Post::seoUrl($model->title);
            $model->excerpt = substr($model->content, 0, 150);
            if (true == $model->published) {
                $model->published_at = Carbon::now()->toDateTimeString();
                $model->published = true;
            } else {
                $model->published = false;
            }
        });

        static::created(function($model) {
            $model->subscriptions()->create(['user_id' => $model->user_id]);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
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
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
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
     * @return Post|\Illuminate\Database\Eloquent\Builder
     */
    public function getPublishedPosts()
    {
        return $this
            ->latest('published_at')
            ->published();
    }

    /**
     * @param Builder $query
     */
    public function scopePublished(Builder $query)
    {
        $query
            ->where('published_at', '<=', Carbon::now())
            ->where('published', '=', true)
            ->with('attachments', 'answers', 'answers.attachments');
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getPost($id)
    {
        return $this
            ->where('id', '=', $id)
            ->fullPostRelatives()
            ->first();
    }

    /**
     * @param Builder $query
     */
    public function scopeFullPostRelatives(Builder $query)
    {
        $query->with(
            'attachments',
            'user',
            'user.photo',
            'answers',
            'answers.attachments',
            'answers.user',
            'answers.user.photo',
            'answers.comments',
            'comments'
        );
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
        if ($vote = $this->votes()->where(['user_id' => $user->id])->first()) {
            $vote->delete();
        }
    }

    /**
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function subscribers()
    {
        return User::query()
            ->whereIn('id', $this->subscriptions()->pluck('user_id'))
            ->get();
    }

    /**
     * @param $string
     *
     * @return null|string|string[]
     */
    private static function seoUrl($string)
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);

        return $string;
    }
}
