<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'first_name'  => $faker->firstName,
        'middle_name' => $faker->randomLetter,
        'last_name'   => $faker->lastName,
        'email'       => $faker->email,
        'city'        => $faker->city,
        'avatar_url'  => null,
        'password'    => $password ?: $password = bcrypt('secret'),
    ];
});

$factory->define(\App\Models\Contact::class, function (Faker\Generator $faker )
{
    $type = collect(\App\Enums\ContactTypes::humanValues())->random();
    return [
        'type' => $type,
        'value' => $faker->{\App\Enums\ContactTypes::fakeValue($type)},
    ];
});

$factory->define(\App\Dictionary::class, function(Faker\Generator $faker){
    return [
        'keyword' => $faker->word,
        'name' => $faker->words(5, true)
    ];
});


$factory->define(\App\DictionaryValue::class, function(Faker\Generator $faker){
    return [
        'value' => $faker->word,
        'text' => $faker->words(2, true)
    ];
});

