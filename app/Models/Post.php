<?php

namespace App\Models;

use App\Models\Answer;
use App\Models\Subscription;
use App\Models\Vote;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'published', 'user_id'];

    protected static function boot()
    {
        static::saving(function($model) {
            $model->slug = Post::seoUrl($model->title);
            $model->excerpt = substr($model->content, 0, 150);
            if ($model->published == true) {
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

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPublishedPosts()
    {
        return $this->latest('published_at')->published()->get();
    }

    public function scopePublished($query)
    {
        $query->where('published_at', '<=', Carbon::now())
              ->where('published', '=', true);
    }

    public function getScore()
    {
        return $this->votes->sum('score');
    }

    public function voteUp(User $user)
    {
        $vote = $this->votes()->firstOrNew(['user_id' => $user->id]);
        $vote->score = 1;
        $vote->save();
    }

    public function voteDown(User $user)
    {
        $vote = $this->votes()->firstOrNew(['user_id' => $user->id]);
        $vote->score = -1;
        $vote->save();
    }

    public function voteCancel(User $user)
    {
        if ($vote = $this->votes()->where(['user_id' => $user->id])->first())
            $vote->delete();
    }

    public function subscribers()
    {
        return User::whereIn('id', $this->subscriptions()->pluck('user_id'))->get();
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
