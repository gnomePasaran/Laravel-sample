<?php

namespace App\Models;

use App\Mail\PostNotifier;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Traits\AnswerPostTrait;
use App\Models\Traits\VotableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Answer extends Model
{
    use AnswerPostTrait, VotableTrait;

    protected $fillable = ['content', 'user_id'];

    protected static function boot()
    {
        static::created(function($model) {
            foreach ($model->post->subscribers() as $subscriber) {
                Mail::to($subscriber)->send(new PostNotifier($model->post));
            }
        });
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function toggleBest()
    {
        $this->is_best = ! $this->is_best;
        if ($this->is_best) {
            Answer::where('post_id', '=', $this->post_id)->where('is_best', '=', true)->update(['is_best' => false]);
        }
        $this->save();
    }
}
