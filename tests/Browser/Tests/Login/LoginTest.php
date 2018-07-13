<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function testLogin()
    {
        /** @var User $user */
        $user = factory(User::class)->create([
            'email' => 'laravel@laravel.com',
            'password' => bcrypt('secret'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->visit('/login')
                ->assertPathIs('/login')
                ->type('email', $user->email)
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/')
                ->assertSee($user->name);
        });
    }

    /**
     * @throws \Throwable
     */
    public function testSignin()
    {
        $this->browse(function (Browser $browser) {
            $name = 'Test';

            $browser
                ->visit('/register')
                ->type('name', $name)
                ->type('email', 'test@test.com')
                ->type('password', '123123')
                ->type('password_confirmation', '123123')
                ->press('Register')
                ->assertPathIs('/')
                ->assertSee($name);
        });
    }
}
