<?php

namespace App\Enums;

use App\Traits\TEnum;

class VotingForms
{

    use TEnum;


    const FULLTIME = 'очное',
        PARTTIME = 'очно-заочное',
        INABSENTIA = 'заочное';

    public static $values = [
        self::FULLTIME   => 'очное',
        self::PARTTIME   => 'очно-заочное',
        self::INABSENTIA => 'заочное',
    ];
    public static $faker = [
        self::FULLTIME   => 'очное',
        self::PARTTIME   => 'очно-заочное',
        self::INABSENTIA => 'заочное',
    ] ;

}
