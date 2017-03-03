<?php

namespace App\Http\Controllers;

use App\City;
use App\Flat;
use App\House;
use App\Models\User;
use App\Notifications\InvitePeople;
use App\RegisteredFlat;
use App\Sensor;
use App\Street;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Notifications\Messages\MailMessage;

class HousesController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active_new_house = 'active';
        $pageTitle = 'Добавление дома';
        $component = 'app-create-house';
        $cities = City::orderBy('name')->get()->map(function ($city) {
            return [
                'value' => $city->id,
                'text'  => $city->name,
            ];
        });
        return view('houses.create', compact('cities','pageTitle', 'component', 'active_new_house'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @throws \Throwable
     * @throws \Exception
     */
    public function store(Requests\NewHouse $request)
    {
        $house = null;
        \DB::transaction(function () use (&$house, &$request) {
            $city=City::where('name',implode(' ',$request->input('city')))->first();
            if(is_null($city)){
                $city=new City();

                $city->name=implode(' ',$request->input('city'));

                $city->save();
            }

            $street=Street::where('city_id',$city->id)
                ->where('name',$request->input('street_id'))->first();
            if(is_null($street)){
                $street=new Street();
                $street->city_id=$city->id;
                $street->name=$request->input('street_id');

                $street->save();
            }
            $house = new House();
            $house->number = $request->input('number');
            $house->street_id =$street->id;
            $house->square = collect($request->input('flats', []))->sum('square');
            $house->company_id = auth()->user()->company_id;
            $house->save();

            collect($request->input('flats', []))->each(function ($item, $i) use (&$house) {
                $flat = new Flat();
                $flat->number = $i + 1;
                $flat->account_number = $item['account_number'];
                if($item['square']){
                    $flat->square = $item['square'];
                }
                else{
                    $flat->square=0;
                }

                $house->flats()->save($flat);

            });
        });

        return [
            'redirect' => route('houses.show', $house),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(House $house)
    {
        $currentHouse=$house->id;
        return view('houses.show', compact('house','currentHouse'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function load_streets(Request $request){

        $streets=Street::where('city_id',$request->get('city_id'))->orderBy('name')->get()->map(function ($street) {
            return [
                'value' => $street->id,
                'text'  => $street->name,
            ];
        });
        return response()->json(['streets'=>$streets]);

    }
    public function download(House $house,RegisteredFlat $flat)
    {
        $server = config('filesystems.default');
        return response()->flydownload(\Storage::disk($server)->get($flat->scan), basename($flat->scan));
    }
    public function active(House $house,RegisteredFlat $flat)
    {
        $flat->active=true;
        $flat->save();
        return redirect()->back();
    }
    public function send_invite(Request $request){
        $user=new User();
        $user->email=$request->get('email');
        \Notification::send($user, new InvitePeople());
        $this->addToastr('success', 'Приглашение отправлено', 'Успех');
        return redirect()->back();
    }
}
