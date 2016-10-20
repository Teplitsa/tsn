<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ManagerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */

    /** @test */

    public function create_manager_user()
    {
        $user = new \App\Models\User(
            [
                'first_name' => 'Ivan',
                'middle_name' => 'Ivanovich',
                'last_name' => 'Ivanov',
                'email' => 'ivan@fakemail.my',
                'avatar_url' => null,
                'password' => str_random(16),
                'role_id' => '2',
            ]
        );

        $user->save();

        $this->seeInDatabase('users', [
            'first_name' => $user->rawAttribute('first_name'),
            'middle_name' => $user->rawAttribute('middle_name'),
            'last_name' => $user->rawAttribute('last_name'),
            'email' => 'ivan@fakemail.my',
            'avatar_url' => null,
            'role_id' => '2',
        ]);
    }

    /** @test */
    public function which_can_be_seen_on_the_home_page()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $this->actingAs($user);

        $this->visit('/home')
            ->assertResponseOk()
            ->see('Главная')
            ->see('Сотрудники')
            ->see('Добавить дом');
    }

    /** @test */
    public function which_can_be_seen_on_the_house_create_page()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $this->actingAs($user);

        $this->visit('/houses/create')
            ->assertResponseOk()
            ->see('Сотрудники')
            ->see('Добавить дом')
            ->see('Добавление дома')
            ->see('Информация о доме')
            ->see('Адрес')
            ->see('Количество квартир')
            ->see('Информация о квартире')
            ->see('Укажите количество квартир');
    }

    /** @test */
    public function which_can_be_seen_on_the_employees_page()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $this->actingAs($user);

        $this->visit('/employees')
            ->assertResponseOk()
            ->see('Сотрудники')
            ->see('Добавить дом')
            ->see($user->name);
    }

    /** @test */
    public function which_can_be_seen_on_the_employees_page_with_another_employees()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $this->actingAs($user);
        $employee = factory(\App\Models\User::class, 'manager')->create();
        $some_employees = factory(\App\Models\User::class, 'manager', 5)->create();
        /*$employee = new \App\Models\User(
            [
                'first_name' => 'Ivan',
                'middle_name' => 'Ivanovich',
                'last_name' => 'Ivanov',
                'email' => 'ivan@fakemail.my',
                'avatar_url' => null,
                'password' => str_random(16),
                'role_id' => '2',
                'company_id' => $user->company_id,
            ]
        );

        $employee->save();*/

        $this->visit('/employees')
            ->assertResponseOk()
            ->see('Сотрудники')
            ->see('Добавить дом')
            ->see($user->name)
            ->see($employee->name);

        foreach ($some_employees as $employee) {
            $this->visit('/employees')
                ->see($employee->name);
        }
    }

    /** @test */
    public function which_can_be_seen_on_the_employees_create_page()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $this->actingAs($user);

        $this->visit('/employees/create')
            ->assertResponseOk()
            ->see('Добавление сотрудника')
            ->see('Сотрудники')
            ->see('Добавить дом')
            ->see('Имя')
            ->see('Введите ваше имя')
            ->see('Отчество')
            ->see('Введите отчество')
            ->see('Фамилия')
            ->see('Введите фамилию')
            ->see('Email')
            ->see('Контакты')
            ->see('Добавить')
            ->see('Сохранить')
            ->see('Отменить изменения');
    }

    /** @test */
    public function which_can_be_seen_on_the_employees_edit_page()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $this->actingAs($user);

        $this->visit('/employees/1/edit')
            ->assertResponseOk()
            ->see('Редактирование сотрудника')
            ->see('Сотрудники')
            ->see('Добавить дом')
            ->see('Имя')
            ->see($user->first_name)
            ->see('Отчество')
            ->see($user->middle_name)
            ->see('Фамилия')
            ->see($user->last_name)
            ->see('Email')
            ->see($user->email)
            ->see('Контакты')
            ->see('Добавить')
            ->see('Сохранить')
            ->see('Отменить изменения');
    }


    public function does_the_employees_create_page()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $this->actingAs($user);

        $baseAttributes = factory(\App\Models\User::class, 'manager')->make()->getAttributes();
        $data = ['employee' => [array_merge($baseAttributes, ['items' => [], 'id' => null])]];

        $this->json('post', '/employees', $data)
            ->assertResponseOk()
            ->seeJson(['data' => ['redirect' => 'http://localhost/employees']])
            ->seeInDatabase('users', $baseAttributes);
    }

    /** @test */
    public function which_can_be_seen_on_the_house_page()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $house = factory(\App\House::class)->create();
        $flat = factory(\App\Flat::class)->create();
        $this->actingAs($user);

        $this->visit('/houses/1')
            ->assertResponseOk()
            ->see('Сотрудники')
            ->see('Добавить дом')
            ->see($house->address)
            ->see('Охват дома')
            ->see($house->connectedFlats()->count())
            ->see('/')
            ->see($house->flats()->count())
            ->see('квартир подключено к системе')
            ->see('Голосования')
            ->see('Добавить голосование')
            ->see('Голосований не найдено');
    }

    /** @test */
    public function which_can_be_seen_on_the_house_page_with_votings()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $house = factory(\App\House::class)->create();
        $flat = factory(\App\Flat::class)->create();
        $voting = factory(\App\Voting::class)->create();
        $vote_items = factory(\App\VoteItem::class)->create();
        $this->actingAs($user);

        $this->visit('/houses/1')
            ->assertResponseOk()
            ->see('Сотрудники')
            ->see('Добавить дом')
            ->see($house->address)
            ->see('Охват дома')
            ->see($house->connectedFlats()->count())
            ->see('/')
            ->see($house->flats()->count())
            ->see('квартир подключено к системе')
            ->see('Голосования')
            ->see('Добавить голосование')
            ->see($voting->name);

        if ($voting->closed_at > \Carbon\Carbon::now())
        {
            $this->visit('//houses/1')
                ->see('Идет');
        }
        else
        {
            $this->visit('//houses/1')
                ->see('Завершено');
        }
    }

    /** @test */
    public function which_can_be_seen_on_the_votings_create_page()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $house = factory(\App\House::class)->create();
        $flat = factory(\App\Flat::class)->create();
        $this->actingAs($user);

        $this->visit('/houses/1/votings/create')
            ->assertResponseOk()
            ->see('Сотрудники')
            ->see('Добавить дом')
            ->see($house->address)
            ->see('Создание голосования')
            ->see('Вопрос №')
            ->see('Добавить вопрос')
            ->see('Предложение')
            ->see('Вопрос')
            ->see('Введите суть вопроса')
            ->see('Введите понятное название')
            ->see('Информация по голосованию')
            ->see('Название')
            ->see('Введите понятное название для собственников');
    }

    /** @test */
    public function which_can_be_seen_on_the_votings_page()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $house = factory(\App\House::class)->create();
        $flat = factory(\App\Flat::class)->create();
        $voting = factory(\App\Voting::class)->create();
        $vote_items = factory(\App\VoteItem::class)->create();
        $this->actingAs($user);

        $this->visit('/houses/1/votings/1')
            ->assertResponseOk()
            ->see('Сотрудники')
            ->see('Добавить дом')
            ->see($house->address)
            ->see('Информация по голосованию')
            ->see('Информация по голосованию:')
            ->see('Название')
            ->see($voting->name)
            ->see('Крайний срок')
            ->see('Повестка дня')
            ->see('Вопрос №')
            ->see('Предложение')
            ->see($vote_items->description)
            ->see('Вопрос')
            ->see($vote_items->name)
            ->see($vote_items->text);
    }
}
