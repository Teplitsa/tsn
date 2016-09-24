<?php

namespace App\Transformers\Dictionary;

use App\Dictionary;
use App\Transformers\DictionaryValue\All as AllDictionary;

class All
{
    public function transform(Dictionary $dictionary)
    {
        return [
            'id' => $dictionary->id,
            'name' => $dictionary->name,
            'keyword' => $dictionary->keyword,
            'items' =>
                fractal()->collection($dictionary->values)->transformWith(new AllDictionary())->toArray()['data'],
        ];
    }
}
