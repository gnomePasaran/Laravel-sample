<?php

namespace App\Models\Traits;

use App\Models\Vote;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait VotableTrait
 */
trait VotableTrait
{
    /**
     * @return MorphMany
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    /**
     * @param User $user
     */
    public function voteUp(User $user)
    {
        /** @var Vote $vote */
        $vote = $this->votes()->firstOrNew(['user_id' => $user->id]);
        $vote->score = 1;
        $vote->save();
    }

    /**
     * @param User $user
     */
    public function voteDown(User $user)
    {
        /** @var Vote $vote */
        $vote = $this->votes()->firstOrNew(['user_id' => $user->id]);
        $vote->score = -1;
        $vote->save();
    }

    /**
     * @param User $user
     */
    public function voteCancel(User $user)
    {
        if ($vote = $this->votes()->where(['user_id' => $user->id]))
            $vote->delete();
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return (int) $this->votes->sum('score');
    }
}
