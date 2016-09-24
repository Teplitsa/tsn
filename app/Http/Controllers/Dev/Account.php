<?php

namespace App\Http\Controllers\Dev;

use App\Role;
use Hash;
use App\Models\User;
use Faker\Generator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Account extends Controller
{
    public function signIn(User $user)
    {
        auth()->login($user);
        return redirect('/');
    }
    public function registered(User $user)
    {
        $user->registered();
        return 'sent';
    }

    public function signUp()
    {
        /** @var Generator $faker */
        $faker = app()->make(Generator::class);
        $user = new User(
            [
                'first_name'     => $faker->name,
                'middle_name'     => $faker->name,
                'last_name'     => $faker->name,
                'password' => \Hash::make('123456'),
                'email'    => $faker->email,
                'role_id' => Role::all()->random()->id
            ]
        );
        $user->save();
        return $user;
    }
}
