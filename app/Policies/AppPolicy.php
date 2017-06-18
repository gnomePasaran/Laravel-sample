<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppPolicy
{
    public function before($user)
    {
        if ($user->admin) {
          return true;
        }
    }
}
