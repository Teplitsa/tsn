<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisteredFlat extends Model
{
    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
