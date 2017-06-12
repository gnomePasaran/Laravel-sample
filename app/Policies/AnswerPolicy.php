<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Answer;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerPolicy extends AppPolicy
{
    public function update(User $user, Answer $answer)
    {
        return $answer->user_id === $user->id;
    }

    public function edit(User $user, Answer $answer)
    {
        $this->update($user, $answer);
    }

    public function destroy(User $user, Answer $answer)
    {
        $this->update($user, $answer);
    }
}
