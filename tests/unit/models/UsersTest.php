<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class UnitModelsUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function create_user()
    {
        $user = new \App\Models\User(
            [
                'first_name'  => 'John',
                'middle_name' => 'N',
                'last_name'   => 'Doe',
                'email'       => 'john@example.com',
                'city'        => 'Texas',
                'avatar_url'  => null,
                'password' => str_random(16),
            ]
        );

        $user->save();

        $this->seeInDatabase('users', [
            'first_name'  => 'John',
            'middle_name' => 'N',
            'last_name'   => 'Doe',
            'email'       => 'john@example.com',
            'city'        => 'Texas',
            'avatar_url'  => null,
        ]);
    }

}