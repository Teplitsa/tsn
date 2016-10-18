<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TenantTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */

    public function create_tenant_user()
    {
        $user = new \App\Models\User(
            [
                'first_name' => 'Ivan',
                'middle_name' => 'Ivanovich',
                'last_name' => 'Ivanov',
                'email' => 'ivan@fakemail.my',
                'avatar_url' => null,
                'password' => str_random(16),
                'role_id' => '1',
            ]
        );

        $user->save();

        $this->seeInDatabase('users',
            [
                'first_name' => 'Ivan',
                'middle_name' => 'Ivanovich',
                'last_name' => 'Ivanov',
                'email' => 'ivan@fakemail.my',
                'avatar_url' => null,
                'role_id' => '1',
            ]);
    }

    /** @test */
    public function which_can_be_seen_on_the_home_page()
    {
        $user = factory(\App\Models\User::class, 'tenant')->create();
        $this->actingAs($user);

        $this->visit('/home')
            ->assertResponseOk()
            ->see('Главная')
            ->see('Добавить квартиру');
    }

    /** @test */
    public function which_can_be_seen_on_the_flat_page()
    {
        $user = factory(\App\Models\User::class, 'tenant')->create();
        $this->actingAs($user);

        $this->visit('/flats/attach')
            ->assertResponseOk()
            ->see('Добавить квартиру')
            ->see('Подключение квартиры')
            ->see('Введите ваш номер счета')
            ->see('Выберите ТСЖ')
            ->see('Добавить')
            ->see('Для чего нужно привязывать квартиру?')
            ->see('Я не могу найти свое ТСЖ');
    }
}
