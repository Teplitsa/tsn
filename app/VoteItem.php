<?php

namespace App;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class VoteItem extends Model
{
    protected $fillable = [
        'name', 'description', 'text', 'voting_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes(){
        return $this->hasMany(Vote::class);
    }
}
