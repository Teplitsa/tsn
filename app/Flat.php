<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function getAddressAttribute()
    {
        return $this->house->street->name . ', ' . $this->house->number;
    }

    public function getAddressFullAttribute()
    {
        return $this->address . ', кв. ' . $this->number;
    }

    public function votings()
    {
        return $this->house->votings();
    }

    public function activeVotings()
    {
        return $this->votings()->where('closed_at', '>', Carbon::now());
    }

    public function registered_flats()
    {
        return $this->hasMany(RegisteredFlat::class);
    }
}
