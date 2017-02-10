<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'inn',
        'name',
        'kpp',
        'ogrn',
    ];

    public function houses()
    {
        return $this->hasMany(House::class);
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'company_id');
    }
}
