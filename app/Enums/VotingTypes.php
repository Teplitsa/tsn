<?php

namespace App\Enums;

use App\Traits\TEnum;

class VotingTypes
{

    use TEnum;

    const REGULAR = 'очередное',
        IRREGULAR = 'внеочередное';

    public static $values = [
        self::REGULAR => 'очередное',
        self::IRREGULAR => 'внеочередное',
    ];

}
