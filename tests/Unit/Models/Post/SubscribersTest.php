<?php

namespace Tests\Unit\Models\Post;

use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use Tests\TestCase;

class SubscribersTest extends TestCase
{
    /**
     * Gets post's subscribers.
     */
    public function testGetPostSubscribers()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();
        $post->each(function (Post $p) {
            $p->subscriptions()->saveMany(factory(Subscription::class, 1)->create());
        });

        $this->assertEquals(
            User::query()->whereIn('id', $post->subscriptions->pluck('user_id'))->get(),
            $post->subscribers()
        );
    }

    /**
     * Gets exact post's subscribers.
     */
    public function testTwoUserVoted()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();
        $post->each(function (Post $p) {
            $p->subscriptions()->saveMany(factory(Subscription::class, 1)->create());
        });

        /** @var User $otherPost */
        $otherPost = factory(Post::class)->create();
        $otherPost->each(function (Post $p) {
            $p->subscriptions()->saveMany(factory(Subscription::class, 1)->create());
        });

        $this->assertEquals(
            User::query()->whereIn('id', $post->subscriptions->pluck('user_id'))->get(),
            $post->subscribers()
        );;
    }
}
