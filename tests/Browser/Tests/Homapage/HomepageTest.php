<?php

namespace Tests\Browser\Tests\Homepage;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
                ->assertSee('Login');
        });
    }

}