<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    public function sensors()
    {
        return $this->morphMany(Sensor::class, 'sensorable');
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function getAddressAttribute()
    {
        return $this->house->address;
    }

    public function getAddressFullAttribute()
    {
        return $this->house->address . ' кв. ' . $this->number;
    }

    public function votings()
    {
        return $this->hasManyThrough(Voting::class, House::class);
    }

    public function activeVotings()
    {
        return $this->votings()->where('closed_at', '>', Carbon::now());
    }
}
