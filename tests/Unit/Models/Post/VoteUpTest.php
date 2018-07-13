<?php

namespace Tests\Unit\Models\Post;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class VoteUpTest extends TestCase
{
    /**
     * User votes up, and post has only one vote.
     */
    public function testUserVotesUp()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();

        /** @var User $user */
        $user = factory(User::class)->create();

        $post->voteUp($user);

        $this->assertCount(
            1,
            $post->votes->where('user_id', $user->id)->count()
        );
    }

    /**
     * Users vote up, and post has two votes.
     */
    public function testTwoUserVoted()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();

        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var User $otherUser */
        $otherUser = factory(User::class)->create();

        $post->voteUp($user);
        $post->voteUp($otherUser);

        $this->assertEquals(
            2,
            $post->votes->count()
        );;
    }
}
