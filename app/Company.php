<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'inn',
        'name',
        'kpp',
        'ogrn',
    ];

    public function houses()
    {
        return $this->hasMany(House::class);
    }
}
