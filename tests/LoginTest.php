<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */
    public function does_button_register_wokrs()
    {
        $this->visit('/login')
            ->click('Зарегистрироваться')
            ->seePageIs('/register');
        $this->visit('/login')
            ->click('Зарегистрировать ТСЖ')
            ->seePageIs('/new-company');

    }
    /** @test */
    public function that_is_visible_on_the_page()
    {
        $this->visit('/login')
            ->see('Добро пожаловать в Ананас.ТСЖ');
    }


}
