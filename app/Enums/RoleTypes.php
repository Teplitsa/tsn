<?php

namespace App\Enums;

use App\Traits\TEnum;

class RoleTypes
{

    use TEnum;

    const TENANT = 'tenant',
        MANAGER = 'manager';

    public static $values = [
        self::TENANT => 'tenant',
        self::MANAGER => 'manager',
    ];

    public static $faker = [
        self::TENANT => 'Жилец',
        self::MANAGER => 'Менеджер',
    ] ;
}
