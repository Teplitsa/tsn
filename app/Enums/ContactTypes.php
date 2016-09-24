<?php

namespace App\Enums;

use App\Traits\TEnum;

class ContactTypes
{

    use TEnum;

    const SKYPE = 'Skype',
          PHONE = 'Телефон',
          EMAIL = 'Email';

    public static $values = [
        self::SKYPE => 'Skype',
        self::PHONE => 'Телефон',
        self::EMAIL => 'Email',
    ];

    public static $faker = [
        self::SKYPE => 'word',
        self::PHONE => 'phoneNumber',
        self::EMAIL => 'email',
    ] ;
}
