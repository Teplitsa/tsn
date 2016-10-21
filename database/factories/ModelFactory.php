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
        'first_name' => $faker->firstName,
        'middle_name' => $faker->randomLetter,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'avatar_url' => null,
        'password' => $password ?: $password = bcrypt('secret'),
        'role_id' => \App\Role::all()->random()->id,
        'company_id' => null,
    ];
});

$factory->define(App\Company::class, function (Faker\Generator $faker) {

    return [
        'name' => 'fake company',
        'avatar_url' => null,
        'inn' => null,
        'kpp' => null,
        'ogrn' => null,
        'bin' => null,
        'settlement_account' =>null,
        'bank_account' => null,
    ];
});

$factory->define(App\House::class, function (Faker\Generator $faker) {

    return [
        'address' => 'fake address',
        'area' => $faker->randomLetter,
        'company_id' => '1',
    ];
});

$factory->define(App\Flat::class, function (Faker\Generator $faker) {

    return [
        'number' => '1',
        'men_count' => 'fake area',
        'account_number' => $faker->randomLetter,
        'house_id' => '1',
    ];
});

$factory->define(App\Voting::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomLetter,
        'house_id' => '1',
        'closed_at' => $faker->dateTime,
    ];
});

$factory->define(App\VoteItem::class, function (Faker\Generator $faker) {

    return [
        'name' =>$faker->randomLetter,
        'description' => $faker->randomLetter,
        'text' => $faker->randomLetter,
        'voting_id' => '1',
    ];
});

/*$factory->defineAs(App\Models\User::class, 'tenant', function ($faker) {

    $data = $this->raw(App\Models\User::class);

    return $data['role_id'] = \App\Role::scopeByKeyword('tenant')->id;
});*/

$factory->defineAs(App\Models\User::class, 'tenant', function ($faker) use ($factory) {
    $user = $factory->raw(App\Models\User::class);

    return array_merge($user, ['role_id' => '1', 'company_id' => null]);
});

$factory->defineAs(App\Models\User::class, 'manager', function ($faker) use ($factory) {
    $user = $factory->raw(App\Models\User::class);

    return array_merge($user, ['role_id' => '2', 'company_id' => '1']);
});

$factory->define(App\Role::class, function (Faker\Generator $faker) {

    $type = collect(\App\Enums\RoleTypes::humanValues())->random();
    return [
        'keyword' => $type,
        'role' => $faker->{\App\Enums\RoleTypes::fakeValue($type)},
    ];
});

$factory->define(\App\Models\Contact::class, function (Faker\Generator $faker) {
    $type = collect(\App\Enums\ContactTypes::humanValues())->random();
    return [
        'type' => $type,
        'value' => $faker->{\App\Enums\ContactTypes::fakeValue($type)},
    ];
});

$factory->define(\App\Dictionary::class, function (Faker\Generator $faker) {
    return [
        'keyword' => $faker->word,
        'name' => $faker->words(5, true)
    ];
});


$factory->define(\App\DictionaryValue::class, function (Faker\Generator $faker) {
    return [
        'value' => $faker->word,
        'text' => $faker->words(2, true)
    ];
});


