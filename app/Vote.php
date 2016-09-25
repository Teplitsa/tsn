<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{

    public function vote_item(){
        return $this->belongsTo(VoteItem::class);
    }

}
