<?php

namespace App\Traits;

trait Encryptable
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (is_null($value)){
            return $value;
        }

        if (in_array($key, $this->encryptable)) {
            return decrypt($value);
        }

        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function rawAttribute($key, $default = null)
    {
        return array_get($this->attributes, $key, $default);
    }
}
