<?php

namespace App\Http\Controllers;

use App\Company;
use App\House;
use App\Vote;
use App\VoteItem;
use App\Voting;
use Illuminate\Http\Request;

use App\Http\Requests;

class VotingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $company_id = \Auth::user()->company_id;
        $house = House::where('company_id', $company_id)->pluck('id');
        $votings = Voting::whereIn('house_id', $house)->get();
        return view('votings.index', compact('votings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company_id = \Auth::user()->company_id;
        $houses = House::where('company_id', $company_id)->get();
        $voting = new Voting();
        return view('votings.show', compact('voting', 'houses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $voting = null;
        \DB::transaction(function () use($request, &$voting) {
            $voting = new Voting($request->only(['name', 'closed_at', 'house_id']));
            $voting->save();

            collect($request->input('items', []))->each(function ($item) use ($voting) {
                $voteItem = new VoteItem($item);
                $voting->vote_items()->save($voteItem);
            });
        });

        return redirect()->route('votings.show', $voting);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $voting = Voting::where('id', $id)->first();

        $vote_items = VoteItem::orderBy('name')->pluck('name', 'id');
        return view('votings.show', compact('voting', 'vote_items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Voting $voting)
    {
        abort_if(!$this->check($voting), 403);
        $company_id = \Auth::user()->company_id;
        $houses = House::where('company_id', $company_id)->get();
        $vote_items = VoteItem::orderBy('name')->pluck('name', 'id');
        return view('votings.show', compact('voting', 'vote_items', 'houses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voting $voting)
    {
        \DB::transaction(function () use($request, &$voting){
            $voting->fill($request->only('name', 'closed_at', 'house_id'));
            $voting->save();

            collect($request->input('items', []))->each(function ($item) use ($voting) {
                $voteItem = VoteItem::find($item['id']);
                $voting->vote_items()->save($voteItem);
            });

        });
        return redirect()->route('votings.show', $voting);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voting $voting)
    {
        abort_if(!$this->check($voting), 403);
        \DB::transaction(function () use($voting) {

            $voting->delete();
            return redirect()->route('votings.index');
        });
    }

    protected function check(Voting $voting)
    {
        $check = true;
        $voting->vote_items->each(function($vote_item) use(&$check) {
            $check = $check && $vote_item->votes()->count == 0;
        });

        return $check;
    }
}