<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    //
    public function flats()
    {
        return $this->hasMany(Flat::class);
    }

    public function connectedFlats()
    {
        return $this->flats()->whereHas('registered_flats');
    }

    public function votings()
    {
        return $this->hasMany(Voting::class);
    }
}
