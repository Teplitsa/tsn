<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserVoting extends Model
{
    protected $table='user_voting';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
