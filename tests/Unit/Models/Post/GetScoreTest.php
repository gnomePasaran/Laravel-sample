<?php

namespace Tests\Unit\Models\Post;

use App\Models\Post;
use App\Models\Vote;
use Tests\TestCase;

class GetScoreTest extends TestCase
{
    /**
     * Gets score 5.
     */
    public function testGetPositiveScore()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();
        $post->each(function (Post $p) {
            $p->votes()->saveMany(factory(Vote::class, 5)->create(['score' => 1]));
        });

        $this->assertEquals(
            5,
            $post->getScore()
        );
    }

    /**
     * Gets equal random generated score.
     */
    public function testGetRandomScore()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();
        $post->each(function (Post $p) {
            $p->votes()->saveMany(factory(Vote::class, 5)->create());
        });

        $this->assertEquals(
            Vote::query()->get()->sum('score'),
            $post->getScore()
        );
    }

    /**
     * Gets correct self score.
     */
    public function testGetExactScore()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();
        $post->each(function (Post $p) {
            $p->votes()->saveMany(factory(Vote::class, 5)->create());
        });

        /** @var Post $otherPost */
        $otherPost = factory(Post::class)->create();
        $otherPost->each(function (Post $p) {
            $p->votes()->saveMany(factory(Vote::class, 5)->create());
        });

        $this->assertEquals(
            Vote::query()->where('votable_id', $post->id)->sum('score'),
            $post->getScore()
        );
    }
}
