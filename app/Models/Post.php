<?php

namespace App\Models;

use App\Models\Traits\AnswerPostTrait;
use App\Models\Traits\VotableTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
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
    use AnswerPostTrait, Sluggable, VotableTrait;

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

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
     * @param string $slug
     *
     * @return Post
     */
    public function getPost(string $slug): Post
    {
        return $this
            ->where('slug', '=', $slug)
            ->fullPostRelatives()
            ->firstOrFail();
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
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

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
