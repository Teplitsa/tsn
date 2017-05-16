<?php

namespace App\Enums;

use App\Traits\TEnum;

class VotingForms
{

    use TEnum;


    const
        PARTTIME = 'очно-заочное',
        INABSENTIA = 'заочное';

    public static $values = [
        self::PARTTIME   => 'очно-заочное',
        self::INABSENTIA => 'заочное',
    ];
    public static $faker = [
        self::PARTTIME   => 'очно-заочное',
        self::INABSENTIA => 'заочное',
    ] ;

}
