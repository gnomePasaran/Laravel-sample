<?php

namespace Tests\Unit\Models\Post;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class VoteDownTest extends TestCase
{
    /**
     * User votes down, and post has only one vote.
     */
    public function testUserVotesDown()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();

        /** @var User $user */
        $user = factory(User::class)->create();

        $post->voteDown($user);

        $this->assertEquals(
            -1,
            - $post->votes->where('user_id', $user->id)->count()
        );
    }

    /**
     * Users vote down, and post has two votes.
     */
    public function testTwoUserVoted()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();

        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var User $otherUser */
        $otherUser = factory(User::class)->create();

        $post->voteDown($user);
        $post->voteDown($otherUser);

        $this->assertEquals(
            -2,
            - $post->votes->count()
        );;
    }
}
