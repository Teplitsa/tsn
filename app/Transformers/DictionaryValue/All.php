<?php

namespace App\Transformers\DictionaryValue;

use App\DictionaryValue;

class All
{
    public function transform(DictionaryValue $dictionaryValue)
    {
        return [
            'id' => $dictionaryValue->id,
            'text' => $dictionaryValue->text,
            'value' => $dictionaryValue->value,
        ];
    }
}
