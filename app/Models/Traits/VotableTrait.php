<?php

namespace App\Models\Traits;

use App\Models\Vote;
use App\Models\User;

/**
 * Trait VotableTrait
 */
trait VotableTrait
{
    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
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
        if ($vote = $this->votes()->where(['user_id' => $user->id])->first()) {
            $vote->delete();
        }
    }

    public function getScore()
    {
        return $this->votes->sum('score');
    }
}
