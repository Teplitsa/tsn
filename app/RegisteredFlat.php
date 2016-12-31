<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisteredFlat extends Model
{
    protected $fillable = ['square', 'up_part', 'down_part', 'number_doc', 'date_doc', 'issuer_doc'];
    protected $casts = ['date_doc' => 'date'];

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function user()
    {
        return $this->belongsTo(Models\User::class);
    }

    public function getAddressAttribute()
    {
        if ($this->active) {
            return $this->flat->address_full;
        }

        return $this->flat->address;
    }

    public function isOwned($user)
    {
        if (is_null($user)) {
            return false;
        }

        return (int)$this->user_id === (int)$user->id;
    }

    public function getHouseAttribute()
    {
        return $this->flat->house;
    }
}
