<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteItem extends Model
{
    protected $fillable = [
        'name', 'description', 'text', 'voting_id',
    ];


    public function votes(){
        return $this->hasMany(Vote::class);
    }
}
