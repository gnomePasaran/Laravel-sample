<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Answer;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerPolicy extends AppPolicy
{
    public function update(User $user, Answer $answer)
    {
        return $answer->user_id == $user->id;
    }

    public function edit(User $user, Answer $answer)
    {
        return $this->update($user, $answer);
    }

    public function destroy(User $user, Answer $answer)
    {
        return $this->update($user, $answer);
    }

    public function toggleBest(User $user, Answer $answer)
    {
        return $answer->post->user_id == $user->id;
    }

    public function notAthor(User $user, Answer $answer)
    {
        return $answer->user_id != $user->id;
    }
}
