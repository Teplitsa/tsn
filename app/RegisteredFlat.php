<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisteredFlat extends Model
{
    protected $fillable = [
        'square',
        'up_part',
        'down_part',
        'number_doc',
        'issuer_doc',
    ];
    protected $casts = ['date_doc' => 'date'];
    protected $dates = ['date_doc'];
    public function setDateDocAttribute($value = null)
    {
        $this->attributes['date_doc'] = is_object($value) ? $value : \Carbon\Carbon::createFromFormat('m.d.Y',
            $value);
    }
    public function formDateDocAttribute($value)
    {
        return $value->format('m.d.Y');
    }

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function user()
    {
        return $this->belongsTo(Models\User::class);
    }

    public function getAddressAttribute()
    {
        if ($this->active) {
            return $this->flat->address_full;
        }

        return $this->flat->address;
    }

    public function isOwned($user)
    {
        if (is_null($user)) {
            return false;
        }

        return (int)$this->user_id === (int)$user->id;
    }

    public function getHouseAttribute()
    {
        return $this->flat->house;
    }

    public function wasImportantChanged()
    {
        $fields = [
            'flat_id',
            'number_doc',
            'issuer_doc',
            'up_part',
            'down_part',
            'square',
            'scan',
            'date_doc',
        ];
        foreach ($fields as $field) {
            if ($this->original[$field] != $this->attributes[$field]) {
                if($field=='date_doc'){
                    if($this->original[$field]!=$this->attributes[$field]->format('Y-m-d')){
                        return true;
                    }
                }
                else{
                    return true;
                }

            }
        }

        return false;
    }
}
