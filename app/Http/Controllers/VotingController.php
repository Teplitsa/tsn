<?php

namespace App\Http\Controllers;

use App\City;
use App\Company;
use App\Enums\RoleTypesInVoting;
use App\Enums\VotingTypes;
use App\House;
use App\RegisteredFlat;
use App\Street;
use App\UserVoting;
use App\UserVotiong;
use App\Vote;
use App\VoteItem;
use App\Voting;
use App\VotingType;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Element\ListItem;
use PhpOffice\PhpWord\PhpWord;

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
    public function create(House $house)
    {
        $component = 'app-manage-voting';
        $pageTitle = 'Создание голосования';
        $cities = City::orderBy('name')->get()->map(
            function ($city) {
                return [
                    'value' => $city->id,
                    'text' => $city->name,
                ];
            }
        );

        return view('votings.create', compact('cities', 'house', 'component', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, House $house)
    {
        $voting = null;
        \DB::transaction(
            function () use ($request, &$voting, &$house) {
                $voting = new Voting(
                    $request->only(
                        [
                            'name',
                            'kind',
                            'closed_at',
                            'opened_at',
                            'public_at',
                            'end_at',
                            'protocol_at',
                            'election_place',
                            'voting_type',
                            'public_house_id',
                        ]
                    )
                );
                $voting->house_id = $house->id;
                $voting->public_house_id = $house->id;
                $voting->protocol_number = str_random(5);
                $voting->election_place = str_random(5);
                $voting->save();
                collect($request->input('initiators'))->each(
                    function ($item) use ($voting) {
                        $initiator = new UserVoting();
                        $initiator->voting_id = $voting->id;
                        $initiator->user_id = $item;
                        $initiator->role = RoleTypesInVoting::TENANT;
                        $initiator->is_initiator = true;
                        $initiator->save();
                    }
                );

                collect($request->input('items', []))->each(
                    function ($item) use ($voting) {
                        $voteItem = new VoteItem(collect($item)->only(['name', 'description', 'text'])->all());
                        $voteItem->user_id = auth()->id();
                        $voting->vote_items()->save($voteItem);
                    }
                );
            }
        );

        if ($request->user()->company_id == null) {
            return ['redirect' => route('flat.voting', [$request->user()->getFlatIn($house), $voting])];
        } else {
            return ['redirect' => route('houses.votings.show', [$house, $voting])];
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(House $house, Voting $voting)
    {
        abort_if($house->company_id != auth()->user()->company_id, 403);
        abort_if($voting->house_id != $house->id, 403);
        $pageTitle = 'Информация по голосованию: '.$voting->title;
        $component = 'app-voting';

        return view('votings.show', compact('voting', 'house', 'pageTitle', 'component'));
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
        $voting_type = VotingType::orderBy('name')->pluck('name', 'id');

        return view('votings.show', compact('voting', 'vote_items', 'voting_type', 'houses'));
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
        \DB::transaction(
            function () use ($request, &$voting, &$house) {
                $voting = new Voting(
                    $request->only(
                        [
                            'name',
                            'kind',
                            'closed_at',
                            'opened_at',
                            'public_at',
                            'end_at',
                            'protocol_at',
                            'election_place',
                            'voting_type',
                            'public_house_id',
                        ]
                    )
                );
                $voting->house_id = $house->id;
                $voting->public_house_id = $house->id;
                $voting->protocol_number = str_random(5);
                $voting->election_place = str_random(5);
                $voting->save();

                collect($request->input('items', []))->each(
                    function ($item) use ($voting) {
                        $voteItem = new VoteItem(collect($item)->only(['name', 'description', 'text'])->all());
                        $voteItem->user_id = auth()->id();
                        $voting->vote_items()->save($voteItem);
                    }
                );
            }
        );

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
        \DB::transaction(
            function () use ($voting) {

                $voting->delete();

                return redirect()->route('votings.index');
            }
        );
    }

    protected function check(Voting $voting)
    {
        $check = true;
        $voting->vote_items->each(
            function ($vote_item) use (&$check) {
                $check = $check && $vote_item->votes()->count == 0;
            }
        );

        return $check;
    }

    public function add_iniciators()
    {

    }

    public function download(House $house, Voting $voting)
    {
        $month = Carbon::today()->month;
        $dictionary = [
            '/10/' => 'октября',
            '/11/' => 'ноября',
            '/12/' => 'декабря',
            '/1/' => 'января',
            '/2/' => 'февраля',
            '/3/' => 'марта',
            '/4/' => 'апреля',
            '/5/' => 'мая',
            '/6/' => 'июня',
            '/7/' => 'июля',
            '/8/' => 'августа',
            '/9/' => 'сентября',
        ];
        $month = preg_replace(array_keys($dictionary), array_values($dictionary), $month);
        $public_at_month = preg_replace(array_keys($dictionary), array_values($dictionary), $voting->public_at->month);
        $open_at_month = preg_replace(array_keys($dictionary), array_values($dictionary), $voting->opened_at->month);
        $closed_at_month = preg_replace(array_keys($dictionary), array_values($dictionary), $voting->closed_at->month);
        $name = 'documents/results/'.$house->city->id.'/'.$house->street->id.'/'.$house->number.'/'.$voting->id.'.docx';
        /*if(file_exists($name)){
            return response()->download($name);
        }
        else{*/
        $phpWord = new PhpWord();
        $document = $phpWord->loadTemplate('documents/templates/protocol_template.docx');
        $document->setValue('NAME', $voting->protocol_number);
        $document->setValue('day', Carbon::today()->day);
        $document->setValue('month', $month);
        $document->setValue('year', Carbon::today()->year);
        $document->setValue('city', $house->city->name);
        $document->setValue('street', $house->street->name);
        $document->setValue('house', $house->number);
        $document->setValue('voting_type', $voting->voting_type);
        $document->setValue('kind', $voting->kind);
        $document->setValue('public_at_day', $voting->public_at->day);
        $document->setValue('public_at_month', $public_at_month);
        $document->setValue('public_at_year', $voting->public_at->year);
        $document->setValue('public_at_hours', $voting->public_at->hour);
        $document->setValue('public_at_minutes', $voting->public_at->format('m'));
        $document->setValue('end_at_hours', $voting->end_at->hour);
        $document->setValue('end_at_minutes', $voting->end_at->format('m'));
        $document->setValue('public_city', $voting->public_house->city->name);
        $document->setValue('public_street', $voting->public_house->street->name);
        $document->setValue('public_house', $voting->public_house->number);
        $document->setValue('opened_at_day', $voting->opened_at->day);
        $document->setValue('opened_at_month', $open_at_month);
        $document->setValue('opened_at_year', $voting->opened_at->year);
        $document->setValue('opened_at_hours_and_minutes', $voting->opened_at->format('H.m'));
        $document->setValue('closed_at_day', $voting->closed_at->day);
        $document->setValue('closed_at_month', $closed_at_month);
        $document->setValue('closed_at_year', $voting->closed_at->year);
        $document->setValue('closed_at_hours_and_minutes', $voting->closed_at->format('H.m'));
        $document->setValue('total_square', $house->flats->sum('square'));
        $square = 0;
        $idS = [];
        foreach ($voting->vote_items as $key => $voteItem) {
            array_push($idS, $voteItem->id);
        }

        $uniq = Vote::whereIn('vote_item_id', $idS)->get()->groupBy('registered_flat_id')->count();

        foreach (Vote::whereIn('vote_item_id', $idS)->get()->groupBy('registered_flat_id') as $item) {
            $square += $item->first()->registered_flat->square;
        }
        $document->setValue('votes_total_square', $square);
        $document->setValue('total_votes', $uniq);
        $vote_items = '';
        $vote_items_new = '';
        $full_text = '';
        foreach ($voting->vote_items as $key => $item) {
            $k = $key + 1;
            $full_text = $full_text.'По вопросу № '.$k.' повестки дня "'.$item->name.'".<w:br /> ВЫСТУПАЛ '
                .$item->user->full_name.'<w:br />'.'ПРЕДЛОЖЕНО по пункту №'.$k.' повестки дня: <w:br />'.$item->description.
            '<w:br />. Результаты голосования по пункту №'.$k.' повестки дня: <w:br />'.
                '- ЗА '.$item->votes->where('pro',1)->count().' голосов, что составляет '.
                $item->votes->where('pro',1)->count()*100/$uniq.'% от общего числа голосов собственников, принявших участие в голосовании. <w:br />'.
                '- ПРОТИВ '.$item->votes->where('contra',1)->count().' голосов, что составляет '.
                $item->votes->where('contra',1)->count()*100/$uniq.'% от общего числа голосов собственников, принявших участие в голосовании. <w:br />'.
                '- ВОЗДЕРЖАЛСЯ '.$item->votes->where('refrained',1)->count().' голосов, что составляет '.
                $item->votes->where('refrained',1)->count()*100/$uniq.'% от общего числа голосов собственников, принявших участие в голосовании. <w:br />';

            ;
            $vote_items = $vote_items.'5.'.$k.' '.$item->name.'<w:br />';
            $vote_items_new = $vote_items_new.$k.'. '.$item->name.'<w:br />';}

        $initiators = '';
        foreach (UserVoting::where('voting_id', $voting->id)->get() as $key => $initiator) {
            $k = $key + 1;
            $initiators = $initiators.'1.'.$k.' Собственник кв. №'.$initiator->user->registeredFlats->first(
                )->flat->number.' - '.$initiator->user->full_name.
                '(свидетельство о государственной регистрации права '.$initiator->user->registeredFlats->first(
                )->number_doc.' от '.$initiator->user->registeredFlats->first()->date_doc->format(
                    'd.m.Y'
                ).'г.)'.'<w:br />';
        }
        $document->setValue('vote_items', $vote_items);
        $document->setValue('full_text', $full_text);
        $document->setValue('vote_items_new', $vote_items_new);
        $document->setValue('initiators', $initiators);
        $document->setValue('votes_percent', $uniq / count($house->flats) * 100);
        if ($uniq / count($house->flats) * 100 > 50) {
            $result = 'правомочно';
        } else {
            $result = 'не правомочно';
        }
        $document->setValue('is_success', $result);


        //$document->cloneBlock('vote_items');

        //$section = $phpWord->addSection();
        // $listItem = new ListItem("Test");

        /*$xml = new XMLWriter();
        $listItemWriter = new \PhpOffice\PhpWord\Writer\Word2007\Element\ListItem($xml, $listItem);
        $listItemWriter->write();*/


        //$document->setValue('vote_items', $xml->getData());

//        $section ="";
//        foreach ($voting->vote_items as $key=>$item){
//            $section=$section."5.".$key.$item->name."\n";
//        }
//        $phpWord->setValue('vote_items',$section);
        if (!file_exists('documents/results/'.$house->city->id.'/'.$house->street->id.'/'.$house->number)) {
            mkdir('documents/results/'.$house->city->id.'/'.$house->street->id.'/'.$house->number, 0777, true);
        }
        $document->saveAs($name);

        return response()->download($name);
    }
    // }
}
