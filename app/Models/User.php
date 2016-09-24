<?php

namespace App\Models;

use App\Notifications\Users\NewColleague;
use App\Notifications\Users\Registered;
use Illuminate\Database\Eloquent\Builder;
use Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'city',
        'api_token',


    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAvatarUrlAttribute($value)
    {
        if (!is_null($value)) {
            return $value;
        } else {
            return \Gravatar::src($this->email ?: 'guest', 200);
        }
    }

    public function getFullNameAttribute()
    {
        return sprintf('%s %s %s', $this->first_name, $this->middle_name, $this->last_name);
    }

    public function contacts()
    {
        return $this->morphMany(\App\Models\Contact::class, 'contactable');
    }

    public function getRenderedContactsAttribute()
    {
        return $this->contacts->map(function (Contact $contact) {
            return $contact->renderedAttributes();
        });
    }

    public function registered()
    {
        $this->notify(new Registered());
        User::notMe()->not($this)->get()->each(function ($notifiable) {
            $notifiable->notify(new NewColleague($this));
        });
    }

    public function scopeNotMe(Builder $builder)
    {
        return $builder->where($this->primaryKey, '!=', auth()->id());
    }

    public function scopeNot(Builder $builder, $user)
    {
        return $builder->where($this->primaryKey, '!=', $user->getKey());
    }
}