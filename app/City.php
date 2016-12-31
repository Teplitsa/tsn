<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function streets()
    {
        return $this->hasMany(Street::class);
    }

    public function getStreetsForVueAttribute()
    {
        // @todo @talantsev rewrite it!

        return $this->streets->map(function ($item){
            //@todo define macro

            return ['value' => $item->id, 'text' => $item->name];

        })->values()->all();
    }
}
