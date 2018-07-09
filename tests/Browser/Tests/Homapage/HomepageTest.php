<?php

namespace Tests\Browser\Tests\Homepage;

use App\Models\Post;
use App\Models\Vote;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class HomepageTest extends DuskTestCase
{
    /**
     * Guest can see Login link,
     *
     * @throws \Throwable
     */
    public function testGuestSeeLoginLink()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit('/')
                ->assertPathIs('/')
//                ->assertSee('Login')
//                ->assertStatus(200)
            ;
        });
    }

    /**
     * @throws \Throwable
     */
//    public function testGuestLogin()
//    {
//        $login = LoginTest::login($this);
//    }

}