<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostControllerTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */

    private function createPost()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit('http://localhost:8000/')
                ->click('a.new-post')
                ->type('title', 'Test Title')
                ->type('content', 'Content')
                ->check('published');
        });
    }

    public function testCreatesPost()
    {
        $this->createPost();
        $this->browse(function (Browser $browser) {
            $browser
                ->assertSee('Test Title');
        });
    }

    public function testCreatesAndShowsPost()
    {
        $this->createPost();
        $this->browse(function (Browser $browser) {
            $browser
                ->press('Create post')
                ->assertSee('Test Title');
        });
    }


}
