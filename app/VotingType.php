<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VotingType extends Model
{
    public function votings()
    {
        return $this->belongsToMany(Voting::class);
    }
}
