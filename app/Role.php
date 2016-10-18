<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeByKeyword(Builder $builder, $key)
    {
        return $builder->where('keyword', $key)->first();
    }
}
