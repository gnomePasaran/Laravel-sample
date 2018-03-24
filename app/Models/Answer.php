<?php

namespace App\Models;

use App\Mail\PostNotifier;
use App\Models\Attachment;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Vote;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Answer extends Model
{
    protected $fillable = ['content', 'user_id'];

    protected static function boot()
    {
        static::created(function($model) {
            foreach ($model->post->subscribers() as $subscriber) {
                Mail::to($subscriber)->send(new PostNotifier($model->post));
            }
        });
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toggleBest()
    {
        $this->is_best = ! $this->is_best;
        if ($this->is_best) {
            Answer::where('post_id', '=', $this->post_id)->where('is_best', '=', true)->update(['is_best' => false]);
        }
        $this->save();
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
        if ($vote = $this->votes()->where(['user_id' => $user->id]))
            $vote->delete();
    }
}
