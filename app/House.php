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

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function connectedFlats()
    {
        return $this->flats()->whereHas('registered_flats');
    }

    public function votings()
    {
        return $this->hasMany(Voting::class);
    }
    public function getAddressAttribute(){
        return 'г. '.$this->street->city->name.', '.$this->street->name.' '.$this->number;
    }

    public function getCityIdAttribute()
    {
        return $this->street->city_id;
    }

    public function getCityAttribute()
    {
        return $this->street->city;
    }
}
