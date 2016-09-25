<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = [
        'type',
        'number',
    ];

    public function sensorable()
    {
        return $this->morphTo();
    }
}
