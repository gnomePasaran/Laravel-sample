<?php

namespace Tests\Browser\Tests\Login;

use Tests\TestCase;
use Laravel\Dusk\Browser;
use App\Models\User;

class LoginTest extends TestCase
{

    /**
     * @param TestCase $test
     *
     * @throws \Throwable
     */
    public function login()
    {
        $user = factory(User::class)->create([
            'email' => 'laravel@laravel.com',
            'password' => 'secret',
        ]);

        $this->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'secret')
            ->press('Login')
            ->assertPathIs('/');
    }

    /**
     * @throws \Throwable
     */
    public function signin()
    {
        $this->browse(function (Browser $browser) {
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
