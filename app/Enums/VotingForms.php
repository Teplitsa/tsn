<?php

namespace App\Enums;

use App\Traits\TEnum;

class VotingForms
{

    use TEnum;

    const FULL_TIME = 'fulltime',
        PART_TIME = 'parttime',
        IN_ABSENTIA = 'absent';

    public static $values = [
        self::FULL_TIME   => 'очное',
        self::PART_TIME   => 'очно-заочное',
        self::IN_ABSENTIA => 'заочное',
    ];
}
