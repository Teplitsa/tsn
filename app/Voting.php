<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    protected $fillable = [
        'name', 'house_id', 'closed_at'
    ];


    public function vote_items(){
        return $this->hasMany(VoteItem::class);
    }
}
