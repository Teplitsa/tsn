<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    public function values()
    {
        return $this->hasMany(DictionaryValue::class);
    }
    protected $fillable = [
        'keywords', 'name'
    ];
}
