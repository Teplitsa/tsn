<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    protected $fillable = [
        'name',
        'house_id',
        'public_house_id',
        'voting_type_id',
        'kind',
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
        return $this->belongsTo(Voting_type::class);
    }

    public function getInfo()
    {
        $data = [
            'name'      => $this->name,
            'closed_at' => $this->closed_at,
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
}
