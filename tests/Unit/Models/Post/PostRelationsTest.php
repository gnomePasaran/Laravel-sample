<?php

namespace Tests\Unit\Models\Post;

use App\Models\Answer;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Vote;
use Tests\TestCase;

class PostRelationsTest extends TestCase
{
    /**
     * A user relation with post test.
     *
     * @return void
     */
    public function testUserRelation()
    {
        $post = factory(Post::class)->make();
        $this->assertEquals(
            $post->user_id,
            $post->user->id
        );
    }

    /**
     * An answer relation with post test.
     */
    public function testAnswerRelation()
    {
        $post = factory(Post::class)->create();
        $post->each(function (Post $p) {
            $p->answers()->save(factory(Answer::class)->make());
        });

        $this->assertEquals(
            $post->answers,
            Answer::query()->get()
        );
    }

    /**
     * A comment relation with post test.
     */
    public function testCommentRelation() {
        $post = factory(Post::class)->create();
        $post->each(function (Post $p) {
            $p->comments()->save(factory(Comment::class)->make());
        });

        $this->assertEquals(
            $post->comments,
            Comment::query()->get()
        );
    }

    /**
     * A subscription relation with post test.
     */
    public function testSubscriptionRelation() {
        $post = factory(Post::class)->make();
        $post->each(function (Post $p) {
            $p->subscriptions()->save(factory(Subscription::class)->make());
        });

        $this->assertEquals(
            $post->subscriptions,
            Subscription::query()->get()
        );
    }

    /**
     * A vote relation with post test.
     */
    public function testVoteRelation() {
        $post = factory(Post::class)->make();
        $post->each(function (Post $p) {
            $p->subscriptions()->save(factory(Vote::class)->make());
        });

        $this->assertEquals(
            $post->votes,
            Vote::query()->get()
        );
    }
}
