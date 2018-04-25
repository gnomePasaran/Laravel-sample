<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUser()
    {
        $post = factory(Post::class)->make();
        $this->assertEquals(
            $post->user_id,
            $post->user->id
        );
    }

    public function testAnswer()
    {
        $post = factory(Post::class)->make();
        $answers = factory(Answer::class, 2)->make();
        $post->answers = $answers;

        $this->assertEquals(
            $post->answers,
            $answers
        );
    }
}
