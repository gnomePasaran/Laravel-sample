<?php

namespace Tests\Browser;

use App\Models\Post;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserPostTest extends DuskTestCase
{

    /**
     * A basic browser test example.
     *
     * @throws \Throwable
     */

    private function createPost()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                ->visit('/')
                ->click('a.new-post')
                ->type('title', 'Test Title')
                ->type('content', 'Content');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCreatesPost()
    {
        $this->createPost();

        $this->browse(function (Browser $browser) {
            $browser
                ->press('Save post')
                ->assertDontSee('Test Title');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCreatesAndShowsPost()
    {
        $this->createPost();

        $this->browse(function (Browser $browser) {
            $browser
                ->check('published')
                ->press('Save post')
                ->assertSee('Test Title');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testAuthorUpdatesPost()
    {
        /** @var User $postAuthor */
        $postAuthor = factory(User::class)->create();

        /** @var Post $post */
        $post = factory(Post::class)->create([
            'user_id' => $postAuthor->id
        ]);


        $this->browse(function (Browser $browser) use ($post, $postAuthor) {
            $newTitle = 'Super edited title';
            $browser->loginAs($postAuthor->id)
                ->visit('/')
                ->click('a.edit-post')
                ->type('title', $newTitle)
                ->check('published')
                ->press('Save post')
                ->assertSee($newTitle);
        });
    }

    /**
     * @throws \Throwable
     */
    public function testNotAuthorDoesNotUpdatesPost()
    {
        /** @var User $postAuthor */
        $notPostAuthor = factory(User::class)->create();

        /** @var Post $post */
        $post = factory(Post::class)->create();

        $this->browse(function (Browser $browser) use ($post, $notPostAuthor) {
            $browser
                ->loginAs($notPostAuthor->id)
                ->visit('/')
                ->assertDontSee('Edit post');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testAuthorDeletesPost()
    {
        /** @var User $postAuthor */
        $postAuthor = factory(User::class)->create();

        /** @var Post $post */
        $post = factory(Post::class)->create([
            'user_id' => $postAuthor->id
        ]);

        $this->browse(function (Browser $browser) use ($post, $postAuthor) {
            $browser
                ->loginAs($postAuthor->id)
                ->visit('/')
                ->click('.delete-post')
                ->assertDontSee($post->title);
        });
    }

    /**
     * @throws \Throwable
     */
    public function testNotAuthorDoesNotDeletesPost()
    {
        /** @var User $postAuthor */
        $notPostAuthor = factory(User::class)->create();

        /** @var Post $post */
        $post = factory(Post::class)->create();

        $this->browse(function (Browser $browser) use ($post, $notPostAuthor) {
            $browser
                ->loginAs($notPostAuthor->id)
                ->visit('/')
                ->assertDontSee('Delete');
        });
    }
}
