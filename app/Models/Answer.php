<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\Vote;
use App\Models\User;

class Answer extends Model
{
    protected $fillable = ['content', 'user_id'];

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

    public function toggle_best()
    {
        $this->is_best = !$this->is_best;
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
