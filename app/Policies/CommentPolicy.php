<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy extends AppPolicy
{
    public function update(User $user, Comment $comment)
    {
        return $comment->user_id == $user->id;
    }

    public function edit(User $user, Comment $comment)
    {
        return $this->update($user, $comment);
    }

    public function destroy(User $user, Comment $comment)
    {
        return $this->update($user, $comment);
    }

    public function notAthor(User $user, Comment $comment)
    {
        return $comment->user_id != $user->id;
    }
}
