<?php

namespace App\Enums;

use App\Traits\TEnum;

class VotingForms
{

    use TEnum;

    const FULL_TIME = 'очное',
        PART_TIME = 'очно-заочное',
        IN_ABSENTIA = 'заочное';

    public static $values = [
        self::FULL_TIME => 'очное',
        self::PART_TIME => 'очно-заочное',
        self::IN_ABSENTIA => 'заочное',
    ];

}
