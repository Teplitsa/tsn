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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function street()
    {
        return $this->belongsTo(Street::class);
    }


    public function connectedFlats()
    {

        return $this->flats()->whereHas('registered_flats', function($query)
        {
            $query->where('active', 1);

        });
    }
    public function connectedFlatsSquare()
    {
       $square=0;
       foreach ($this->flats as $flat){
           foreach ($flat->registered_flats->where('active',1) as $registeredFlat){
               $square+=$registeredFlat->user_share;
           }
       }
       return $square;
    }
    public function getSquareAttribute()
    {
        $total_square = 0;
        foreach ($this->flats as $flat) {
            $total_square += $flat->square;
        }

        return $total_square;
    }
    public function getNotActiveAttribute()
    {
        $count = 0;
        foreach ($this->flats as $flat) {
            foreach ($flat->registered_flats->where('active',0) as $registeredFlat){
                if(!$registeredFlat->active)
                    $count+=1;
            }
        }

        return $count;
    }

    public function votings()
    {
        return $this->hasMany(Voting::class);
    }

    public function getAddressAttribute()
    {
        return 'г. '.$this->street->city->name.', '.$this->street->name.' '.$this->number;
    }

    public function getCityIdAttribute()
    {
        if(isset($this->city)){
            return $this->city->id;
        }
        return null;
    }

    public function getStreetForIdAttribute()
    {
        if(isset($this->street)){
            return $this->street_id;
        }
        return null;
    }


    public function getCityAttribute()
    {
        return $this->street->city;
    }

    public function getUsersAttribute()
    {
        $users = [];
        foreach ($this->flats as $flat) {
            foreach ($flat->registered_flats as $registeredFlat) {
                $users[$registeredFlat->user->id] = $registeredFlat->user->full_name;
            }
        }

        foreach ($this->company->employees as $employee) {
            $users[$employee->id] = $employee->full_name;
        }

        return $users;
    }
}
