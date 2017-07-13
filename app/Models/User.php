<?php

namespace App\Models;

use App\Company;
use App\Notifications\Users\NewColleague;
use App\Notifications\Users\Registered;
use App\Notifications\Users\ResetPassword;
use App\RegisteredFlat;
use App\Role;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Builder;
use Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use Encryptable;

    protected $encryptable = [
        'first_name',
        'middle_name',
        'last_name',
    ];

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
        'role_id',
        'api_token',
        'password',
        'avatar_url',
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
        return $this->contacts->map(
            function (Contact $contact) {
                return $contact->renderedAttributes();
            }
        );
    }

    public function registered()
    {
        $this->notify(new Registered());
        User::notMe()->not($this)->get()->each(
            function ($notifiable) {
                $notifiable->notify(new NewColleague($this));
            }
        );
    }

    public function scopeNotMe(Builder $builder)
    {
        return $builder->where($this->primaryKey, '!=', auth()->id());
    }

    public function scopeNot(Builder $builder, $user)
    {
        return $builder->where($this->primaryKey, '!=', $user->getKey());
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function registeredFlats()
    {
        return $this->hasMany(RegisteredFlat::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isUser()
    {
        return $this->company_id === null;
    }
    public function isNeedNotManager($house)
    {
        if($this->company_id != $house->company_id){
            return true;
        }
        return false;
    }

    public function getFlatIn($house)
    {
        return RegisteredFlat::whereHas(
            'flat',
            function (Builder $builder) use ($house) {
                $builder->where('house_id', $house->id);
            }
        )->first();
    }

    public function wasImportantChanged()
    {
        $fields = ['first_name', 'last_name', 'middle_name'];
        foreach ($fields as $field) {
            if ($this->original[$field] != $this->attributes[$field]) {
                return true;
            }
        }

        return false;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
