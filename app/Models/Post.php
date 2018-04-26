<?php

namespace App\Models;

use App\Models\Answer;
use App\Models\Subscription;
use App\Models\Traits\AnswerPostTrait;
use App\Models\Traits\VotableTrait;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use AnswerPostTrait, Sluggable, VotableTrait;

    protected $fillable = ['title', 'content', 'published', 'user_id'];

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

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

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

    public function scopePublished($query)
    {
        $query
            ->where('published_at', '<=', Carbon::now())
            ->where('published', '=', true)
            ->with('attachments', 'answers', 'answers.attachments');
    }

    public function getPost($slug)
    {
        return $this
            ->where('slug', '=', $slug)
            ->fullPostRelatives()
            ->firstOrFail();
    }

    public function scopeFullPostRelatives($query)
    {
        $query
            ->with(
                'user',
                'user.photo',
                'answers',
                'answers.attachments',
                'answers.user',
                'answers.user.photo'
            );
    }

    public function subscribers()
    {
        return User::query()
            ->whereIn('id', $this->subscriptions()->pluck('user_id'))
            ->get();
    }

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
