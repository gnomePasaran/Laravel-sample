<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Post;

class GuestPostTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testDontCreatePost()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:8000/')
                    ->assertDontSee('New post');
        });
    }

    public function testDontEditPostOnIndex()
    {
        $post = factory(Post::class)->create();

        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:8000/')
                    ->assertDontSee('Edit post');
        });
    }

    public function testDontEditPostOnShow()
    {
        $post = factory(Post::class)->create();

        $this->browse(function (Browser $browser) use ($post) {
            $browser->visit("http://localhost:8000/post/$post->id")
                    ->assertDontSee('Edit post');
        });
    }
}
