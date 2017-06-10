<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\DuskServiceProvider;

class PostControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNewPost()
    {
        $this->browse(function ($browser){
            $browser->visit('/')
                    ->click('New post')
                    ->type('Title', 'title')
                    ->type('Content', 'content')
                    ->check('published')
                    ->press('Create post')
                    ->see('Title');
           });
    }
}
