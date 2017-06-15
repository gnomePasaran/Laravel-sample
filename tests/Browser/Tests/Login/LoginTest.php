<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
// use Laravel\BrowserKitTesting\DatabaseTransactions;
use App\Models\User;

class LoginTest extends DuskTestCase
{
    // use DatabaseTransactions;
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $user = factory(User::class)->create([
            'email' => 'laravel@laravel.com'
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs('/');
        });
    }

    public function testSignin()
    {
        $this->browse(function ($browser) {
            $browser->visit('/register')
                    ->type('name', 'Test')
                    ->type('email', 'test@test.com')
                    ->type('password', 'secret')
                    ->type('password_confirmation', 'secret')
                    ->press('Register')
                    ->assertSee('Test');
        });
    }
}
