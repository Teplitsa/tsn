<?php

namespace App\Http\Controllers;

use App\City;
use App\Flat;
use App\House;
use App\Sensor;
use App\Street;
use Illuminate\Http\Request;

use App\Http\Requests;

class HousesController extends Controller
{
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
            $house = new House();
            $house->number = $request->input('number');
            $house->street_id = $request->input('street_id');
            $house->square = collect($request->input('flats', []))->sum('square');
            $house->company_id = auth()->user()->company_id;
            $house->save();

            collect($request->input('flats', []))->each(function ($item, $i) use (&$house) {
                $flat = new Flat();
                $flat->number = $i + 1;
                $flat->account_number = $item['account_number'];
                $flat->square = $item['square'];

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
        return view('houses.show', compact('house'));
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
}
