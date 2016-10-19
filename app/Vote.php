<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{

    public function vote_item(){
        return $this->belongsTo(VoteItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
