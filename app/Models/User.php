<?php

namespace App\Models;

use App\Models\Answer;
use App\Models\Attachment;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Vote;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function photo()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function isSubscribed(Post $post)
    {
        return !! $this->subscriptions()->where(['post_id' => $post->id])->first();
    }

    public function subscribe(Post $post)
    {
        if ($this->isSubscribed($post)){
            $this->subscriptions()->where(['post_id' => $post->id])->first()->delete();
        } else {
            $this->subscriptions()->create(['post_id' => $post->id]);
        }
    }

    public function getScore()
    {
        return $this->votes->sum('score');
    }

    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->remember_token;
    }
}
