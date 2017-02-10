<?php
/**
 * Created by PhpStorm.
 * User: kseni
 * Date: 04.02.2016
 * Time: 23:32
 */

namespace App\Traits;

trait TEnum
{
    public static function humanValues($valuesNeeded = null)
    {
        if ($valuesNeeded === null) {
            $valuesNeeded = self::db();
        }

        return array_only(self::$values, $valuesNeeded);
    }

    public static function db()
    {
        return array_keys(self::$values);
    }

    public static function humanValue($key)
    {
        return isset(self::$values[$key]) ? self::$values[$key] : '';
    }

    public static function fakeValue($key)
    {
        return isset(self::$faker[$key]) ? self::$faker[$key] : 'word';
    }
    public static function humanValuesForVue()
    {
        return collect(self::humanValues())->map(
            function ($val, $key) {
                return ['value' => $key, 'text' => $val];
            }
        )->values()->all();
    }


}
