<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class UserPostTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A basic browser test example.
     *
     * @return void
     */

    private function createPost()
    {
        $user = factory(User::class)->create([
            'email' => 'laravel@laravel.com'
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                ->visit('http://localhost:8000/')
                ->click('a.new-post')
                ->type('title', 'Test Title')
                ->type('content', 'Content');
        });
    }

    public function testCreatesPost()
    {
        $this->createPost();
        $this->browse(function (Browser $browser) {
            $browser
                ->press('Create post')
                ->assertDontSee('Test Title');
        });
    }

    public function testCreatesAndShowsPost()
    {
        $this->createPost();
        $this->browse(function (Browser $browser) {
            $browser
                ->check('published')
                ->press('Create post')
                ->assertSee('Test Title');
        });
    }


}
