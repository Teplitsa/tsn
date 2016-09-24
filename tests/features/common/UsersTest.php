<?php

use App\Notifications\Users\NewColleague;
use App\Notifications\Users\Registered;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FeaturesCommonUsersTest extends TestCase
{
    use DatabaseMigrations, InteractsWithDatabase;

    public function test_user_can_see_list()
    {
        $user = factory(\App\Models\User::class)->create();
        $this->actingAs($user);

        $this->visit('/employees')
            ->assertResponseOk();
    }

    /** @test */
    public function does_all_users_display_on_employees_page()
    {
        // Подготовка
        $users = factory(\App\Models\User::class, 10)->create();
        $this->actingAs($users[0]);

        //Действия
        $this->visit('/employees');

        //Проверка
        $this
            ->see($users[1]->full_name)
            ->see($users[2]->full_name)
            ->see($users[3]->full_name)
            ->see($users[4]->full_name)
            ->see($users[5]->full_name)
            ->see($users[6]->full_name)
            ->see($users[7]->full_name);

    }

    /** @test */
    public function add_employee_page_exists()
    {
        $users = factory(\App\Models\User::class)->create();
        $this->actingAs($users);

        $this->visit('/employees/create')
            ->assertResponseOk();

        $this
            ->see('Имя')
            ->see('Отчество')
            ->see('Добавление сотрудника')
            ->see('Фамилия')
            ->see('Email');
    }

    /** @test */
    public function add_employee_handle()
    {
        Notification::fake();

        $users = factory(\App\Models\User::class, 5)->create();
        $this->actingAs($users[0]);

        $requestData = $userInfo = [
            'first_name' => 'John',
            'last_name'  => 'Smith',
            'city'       => 'Sweetland',
            'email'      => 'john@example.com',
        ];
        $requestData['contacts'] = factory(\App\Models\Contact::class, 3)->make();


        $checker = function ($notification) use ($userInfo) {
            return $notification->colleague->first_name === $userInfo['first_name'] &&
            $notification->colleague->last_name === $userInfo['last_name'] &&
            $notification->colleague->city === $userInfo['city'] &&
            $notification->colleague->email === $userInfo['email'];
        };

        $this->json('POST', '/employees', $requestData);

        $this->seeJson([
            'data' => [
                'redirect' => route('employees.index'),
            ],
        ])
            ->seeInDatabase('users', $userInfo)
            ->seeInDatabase('contacts', $requestData['contacts'][0]->getAttributes())
            ->seeInDatabase('contacts', $requestData['contacts'][1]->getAttributes())
            ->seeInDatabase('contacts', $requestData['contacts'][2]->getAttributes());

        self::assertCount(3, \App\Models\Contact::all());


        Notification::assertNotSentTo(\App\Models\User::latest()->first(), NewColleague::class);
        Notification::assertNotSentTo($users[0], NewColleague::class);

        Notification::assertSentTo(\App\Models\User::all()->last(), Registered::class,
            function ($notification) use ($userInfo) {
                return true;
            });

        Notification::assertSentTo($users[1], NewColleague::class, $checker);
        Notification::assertSentTo($users[2], NewColleague::class, $checker);
        Notification::assertSentTo($users[3], NewColleague::class, $checker);
        Notification::assertSentTo($users[4], NewColleague::class, $checker);
    }


    /** @test */
    public function edit_employee_page_exists()
    {
        $user = factory(\App\Models\User::class)->create();
        $editable_user = factory(\App\Models\User::class)->create();
        $this->actingAs($user);

        $this->visit('/employees/' . $editable_user->getKey() . '/edit')
            ->assertResponseOk();

        $this
            ->see('Имя')
            ->see('Отчество')
            ->see('Фамилия')
            ->see('Email')
            ->see($editable_user->first_name)
            ->see($editable_user->last_name)
            ->see($editable_user->middle_name)
            ->see('Редактирование сотрудника')
            ->see($editable_user->email);
    }

    /** @test */
    public function edit_employee_handle()
    {
        Notification::fake();

        $user = factory(\App\Models\User::class)->create();
        $test_user = factory(\App\Models\User::class)->create();
        /** @var \App\Models\User $editable_user */
        $editable_user = factory(\App\Models\User::class)->create();
        $this->actingAs($user);

        $contacts = factory(\App\Models\Contact::class, 3)->make();
        $editable_user->contacts()->saveMany($contacts);


        $oldInfo = $editable_user->getAttributes();
        $requestData = $userInfo = [
            'first_name'  => 'John',
            'middle_name' => 'N',
            'last_name'   => 'Smith',
            'city'        => 'Sweetland',
            'email'       => 'john@example.com',
        ];

        $requestData['contacts'] = [$contacts[0], $contacts[2], factory(\App\Models\Contact::class)->make()];


        $this->seeInDatabase('users', $oldInfo);
        $this->json('PATCH', '/employees/' . $editable_user->getKey(), $requestData);

        $this->seeJson([
            'data' => [
                'redirect' => route('employees.index'),
            ],
        ])
            ->seeInDatabase('users', $userInfo)
            ->dontSeeInDatabase('contacts', $contacts[1]->getAttributes())
            ->seeInDatabase('contacts', $requestData['contacts'][0]->getAttributes())
            ->seeInDatabase('contacts', $requestData['contacts'][1]->getAttributes())
            ->seeInDatabase('contacts', $requestData['contacts'][2]->getAttributes());
        self::assertCount(3, \App\Models\Contact::all());



        Notification::assertNotSentTo($editable_user, NewColleague::class);
        Notification::assertNotSentTo($test_user, NewColleague::class);
        Notification::assertNotSentTo($user, NewColleague::class);
        Notification::assertNotSentTo($editable_user, Registered::class);

    }
}