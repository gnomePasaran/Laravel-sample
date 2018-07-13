<?php

namespace Tests\Browser;

use App\Models\Post;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GuestPostTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @throws \Throwable
     */
    public function testDoesNotCreatePost()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit('/')
                ->assertDontSee('New post');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testDoesNotEditPostOnIndex()
    {
        $post = factory(Post::class)->create();

        $this->browse(function (Browser $browser) {
            $browser
                ->visit('/')
                ->assertDontSee('Edit post');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testDoesNotEditPostOnShow()
    {
        $post = factory(Post::class)->create();

        $this->browse(function (Browser $browser) use ($post) {
            $browser
                ->visit("/post/$post->id")
                ->assertDontSee('Edit post');
        });
    }
}
