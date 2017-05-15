<?php

namespace App\Http\Controllers;

use App\Flat;
use App\House;
use App\RegisteredFlat;
use App\Vote;
use App\VoteItem;
use App\Voting;
use Illuminate\Http\Request;

use App\Http\Requests;

class VoteController extends Controller
{
    public function voting(RegisteredFlat $flat, Voting $voting)
    {
        abort_if($flat->user_id != auth()->user()->id, 403);
        abort_if($flat->flat->house_id != $voting->house_id, 403);
        $component = 'app-voting';
        $pageTitle = 'Голосование';
        return view('votes.show', compact('flat', 'voting', 'component', 'pageTitle'));
    }

    public function vote(RegisteredFlat $flat, Voting $voting, VoteItem $votingItem, $result)
    {
        abort_if($flat->user_id != auth()->user()->id, 403);
        abort_if($flat->flat->house_id != $voting->house_id, 403);
        abort_if($votingItem->voting_id != $voting->id, 403);

        $vote = $votingItem->votes->first(function ($vote) use ($flat){
            return $vote->registered_flat_id == $flat->id;
        });

        if($vote == null)
        {
            $vote = new Vote();
            $vote->registered_flat()->associate($flat);
            $vote->vote_item()->associate($votingItem);
        }

        $vote->pro = $result == '1';
        $vote->contra = $result == '-1';
        $vote->refrained = $result == '0';
        $vote->save();
        $this->addToastr('success', 'Ваш голос учтен', 'Спасибо!');
        return ['success'=>'true'];
    }
}
