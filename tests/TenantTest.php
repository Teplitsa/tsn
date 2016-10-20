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
                'first_name' => $user->rawAttribute('first_name'),
                'middle_name' => $user->rawAttribute('middle_name'),
                'last_name' => $user->rawAttribute('last_name'),
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
    public function which_can_be_seen_on_the_flat_attach_page()
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

    /** @test */
    public function which_can_be_seen_on_the_flat_attach_page_with_company()
    {
        $company = factory(\App\Company::class)->create();
        $manager_user = factory(\App\Models\User::class, 'manager')->create();
        $tenant_user = factory(\App\Models\User::class, 'tenant')->create();
        $this->actingAs($tenant_user);

        $this->visit('/flats/attach')
            ->assertResponseOk()
            ->see('Добавить квартиру')
            ->see('Подключение квартиры')
            ->see('Введите ваш номер счета')
            ->see('Выберите ТСЖ')
            ->see($company -> name)
            ->see('Добавить')
            ->see('Для чего нужно привязывать квартиру?')
            ->see('Я не могу найти свое ТСЖ');
    }

    /** @test */
    public function which_can_be_seen_on_the_flat_page_before_activate()
    {
        $company = factory(\App\Company::class)->create();
        $manager_user = factory(\App\Models\User::class, 'manager')->create();
        $tenant_user = factory(\App\Models\User::class, 'tenant')->create();
        $this->actingAs($tenant_user);
        $house = factory(\App\House::class)->create();
        $flat = factory(\App\Flat::class)->create();
        $registered_flat = new \App\RegisteredFlat(
            [
                'user_id' => $tenant_user->id,
                'flat_id' => $flat->id,
                'active' => '0',
                'activate_code' => str_random(16),
            ]
        );

        $registered_flat->save();

        $this->seeInDatabase('registered_flats',
            [
                'user_id' => $tenant_user->id,
                'flat_id' => $flat->id,
                'active' => '0',
            ]);

        $this->visit('/flats/1')
            ->assertResponseOk()
            ->see('Добавить квартиру')
            ->see('Заявка на подключение успешно создана')
            ->see('Код активации')
            ->see('Активировать');
    }

    /** @test */
    public function which_can_be_seen_on_the_flat_page_after_activate()
    {
        $company = factory(\App\Company::class)->create();
        $manager_user = factory(\App\Models\User::class, 'manager')->create();
        $tenant_user = factory(\App\Models\User::class, 'tenant')->create();
        $this->actingAs($tenant_user);
        $house = factory(\App\House::class)->create();
        $flat = factory(\App\Flat::class)->create();
        $registered_flat = new \App\RegisteredFlat(
            [
                'user_id' => $tenant_user->id,
                'flat_id' => $flat->id,
                'active' => '1',
                'activate_code' => str_random(16),
            ]
        );

        $registered_flat->save();

        $this->seeInDatabase('registered_flats',
            [
                'user_id' => $tenant_user->id,
                'flat_id' => $flat->id,
                'active' => '1',
            ]);

        $this->visit('/flats/1')
            ->assertResponseOk()
            ->see('Добавить квартиру')
            ->see('Голосование')
            ->see('Активных голосований нет');
    }

    /** @test */
    public function which_can_be_seen_on_the_flat_page_after_activate_with_votings()
    {
        $company = factory(\App\Company::class)->create();
        $manager_user = factory(\App\Models\User::class, 'manager')->create();
        $tenant_user = factory(\App\Models\User::class, 'tenant')->create();
        $this->actingAs($tenant_user);
        $house = factory(\App\House::class)->create();
        $flat = factory(\App\Flat::class)->create();
        $voting = factory(\App\Voting::class)->create();
        $vote_items = factory(\App\VoteItem::class)->create();
        $registered_flat = new \App\RegisteredFlat(
            [
                'user_id' => $tenant_user->id,
                'flat_id' => $flat->id,
                'active' => '1',
                'activate_code' => str_random(16),
            ]
        );

        $registered_flat->save();

        $this->seeInDatabase('registered_flats',
            [
                'user_id' => $tenant_user->id,
                'flat_id' => $flat->id,
                'active' => '1',
            ]);

        $this->visit('/flats/1')
            ->assertResponseOk()
            ->see('Добавить квартиру')
            ->see('Голосование')
            ->see($voting->name);
    }


    /** @test */
    public function which_can_be_seen_on_the_voting_page()
    {
        $company = factory(\App\Company::class)->create();
        $manager_user = factory(\App\Models\User::class, 'manager')->create();
        $tenant_user = factory(\App\Models\User::class, 'tenant')->create();
        $this->actingAs($tenant_user);
        $house = factory(\App\House::class)->create();
        $flat = factory(\App\Flat::class)->create();
        $voting = factory(\App\Voting::class)->create();
        $vote_items = factory(\App\VoteItem::class)->create();
        $registered_flat = new \App\RegisteredFlat(
            [
                'user_id' => $tenant_user->id,
                'flat_id' => $flat->id,
                'active' => '1',
                'activate_code' => str_random(16),
            ]
        );

        $registered_flat->save();

        $this->seeInDatabase('registered_flats',
            [
                'user_id' => $tenant_user->id,
                'flat_id' => $flat->id,
                'active' => '1',
            ]);

        $this->visit('/flat/1/voting/1')
            ->assertResponseOk()
            ->see('Добавить квартиру')
            ->see('Голосование')
            ->see('Информация по голосованию')
            ->see('Название')
            ->see($voting->name)
            ->see('Крайний срок')
            ->see('Повестка дня')
            ->see('Вопрос №')
            ->see('Предложение')
            ->see($vote_items->description)
            ->see('Вопрос')
            ->see($vote_items->name)
            ->see($vote_items->text)
            ->see('За')
            ->see('Против')
            ->see('Воздержусь');
    }
}
