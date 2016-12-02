<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voting_type extends Model
{
    public function votings()
    {
        return $this->belongsToMany(Voting::class);
    }
}
