<?php

namespace App\Enums;

use App\Traits\TEnum;

class RoleTypesInVoting
{

    use TEnum;

    const CHAIRMAN = 'chairman',
        SECRETARY = 'secretary',
        TENANT = 'tenant',
        COUNTER = 'counter';

    public static $values = [
        self::CHAIRMAN => 'chairman',
        self::SECRETARY => 'secretary',
        self::TENANT => 'tenant',
        self::COUNTER => 'counter',
    ];

    public static $faker = [
        self::CHAIRMAN => 'Председатель',
        self::SECRETARY => 'Секретарь',
        self::TENANT => 'Жилец',
        self::COUNTER => 'Счетная комиссия',
    ] ;
}
