<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisteredFlat extends Model
{
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

    protected $fillable = [
        'user_id',
        'flat_id',
        'active',
        'activate_code',
    ];
}
