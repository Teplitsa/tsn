<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    protected $fillable = [
        'name',
        'house_id',
        'public_house_id',
        'voting_type',
        'kind',
        'end_at',
        'election_at',
        'opened_at',
        'closed_at',
        'public_at',
        'public_length',
        'protocol_at',
    ];


    public function vote_items()
    {
        return $this->hasMany(VoteItem::class);
    }

    public function voting_type()
    {
        return $this->belongsTo(VotingType::class);
    }

    public function getInfo()
    {
        $data = [
            'name'      => $this->name,
            'closed_at' => $this->closed_at->format('d.m.Y H:m'),
            'items'     => [],
        ];

        foreach ($this->vote_items as $i => $vote_item) {
            $vote = $vote_item->votes->first(function ($vote) {
                return $vote->user_id == auth()->user()->id;
            });

            if ($vote == null) {
                $v = '';
            }
            else
            {
                $v = $vote->pro + (-1)  * $vote->contra;
            }


            $data['items'][] = [
                'i'           => $i,
                'id'          => $vote_item->id,
                'name'        => $vote_item->name,
                'description' => $vote_item->description,
                'text'        => $vote_item->text,

                'pro'       => $vote_item->votes->sum('pro'),
                'contra'    => $vote_item->votes->sum('contra'),
                'refrained' => $vote_item->votes->sum('refrained'),
                'total'     => $vote_item->votes->count(),

                'v' => $v,
            ];
        }

        return $data;
    }

    protected $dates = ['public_at', 'closed_at', 'end_at','opened_at','protocol_at'];

    public function setClosedAtAttribute($value = null)
    {
        $this->attributes['closed_at'] = is_object($value) ? $value : \Carbon\Carbon::createFromFormat('d.m.Y H:m',
            $value);
    }
    public function setProtocolAtAttribute($value = null)
    {
        if($value == null)
            $value = new Carbon();
        $this->attributes['protocol_at'] = is_object($value) ? $value : \Carbon\Carbon::createFromFormat('d.m.Y H:m',
            $value);
    }
    public function setPublicAtAttribute($value = null)
    {
        $this->attributes['public_at'] = is_object($value) ? $value : \Carbon\Carbon::createFromFormat('d.m.Y H:m',
            $value);
    }

    public function setOpenedAtAttribute($value = null)
    {
        $this->attributes['opened_at'] = is_object($value) ? $value : \Carbon\Carbon::createFromFormat('d.m.Y H:m',
            $value);
    }

    public function setEndAtAttribute($value = null)
    {
        $this->attributes['end_at'] = is_object($value) ? $value : \Carbon\Carbon::createFromFormat('d.m.Y H:m',
            $value);
    }


}
