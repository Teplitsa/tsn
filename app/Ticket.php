<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
