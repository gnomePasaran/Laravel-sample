<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy extends AppPolicy
{
    public function update(User $user, Post $post)
    {
        return $post->user_id == $user->id;
    }

    public function edit(User $user, Post $post)
    {
        return $this->update($user, $post);
    }

    public function destroy(User $user, Post $post)
    {
        return $this->update($user, $post);
    }

    public function notAthor(User $user, Post $post)
    {
        return $post->user_id != $user->id;
    }
}
