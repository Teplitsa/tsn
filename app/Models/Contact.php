<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'value',
    ];

    public function contactable()
    {
        return $this->morphTo();
    }

    public function renderedAttributes()
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'type' => $this->type,
        ];
    }
}
