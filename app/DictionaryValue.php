<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DictionaryValue extends Model
{
    protected $with = ['dictionary'];

    public function dictionary()
    {
        return $this->belongsTo(Dictionary::class);
    }
    protected $fillable = [
        'value', 'text', 'dictionary_id'
    ];
}
