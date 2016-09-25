<?php

namespace App\Http\Controllers;

use App\Flat;
use App\House;
use App\Vote;
use App\VoteItem;
use App\Voting;
use Illuminate\Http\Request;

use App\Http\Requests;

class VoteController extends Controller
{
    public function voting(Flat $flat, Voting $voting)
    {
        abort_if($flat->house_id == $voting->house_id, 403);

            $data = [
                'name' => $voting->name,
                'closed_at' => $voting->closed_at,
                'items' => [],
            ];

            foreach ($voting->vote_items() as $vote_item)
                $data['items'][] = [
                    $vote_item->name,
                    $vote_item->description,
                    $vote_item->text,
                ];

        return view('votes.index', compact('voting', 'vote_item'));
    }

    public function vote(Flat $flat, Voting $voting, VoteItem $vote_item, $result)
    {
        abort_if($flat->house_id == $voting->house_id, 403);
        abort_if($vote_item->voting_id == $voting->id, 403);
        $vote = new Vote();
        $vote->pro = $result == '1';
        $vote->contra = $result == '-1';
        $vote->refrained = $result == '0';
        $vote -> save();
        return view('votes.index', compact('vote', 'vote_item'));
    }
}
