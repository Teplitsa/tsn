<?php

namespace App\Http\Controllers;

use App\Company;
use App\House;
use App\RegisteredFlat;
use App\Vote;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flats = [];
        if (auth()->user()->isUser()) {
            $flats = auth()->user()->registeredFlats;
        } else {
            $user = auth()->user();
            $company = Company::find($user->id);
            if (!is_null($company)) {
                $flats = $company->houses->flatMap(function ($house) {

                        return $house->connectedFlats->flatMap(function ($flat) {
                            return $flat->registered_flats;
                        });
                });
            }
        }
        return view('home', ['pageTitle' => 'Главная', 'flats' => $flats]);
    }
    public function show(House $house,RegisteredFlat $flat,Request $request){
        $pageTitle=$flat->flat->address_full;
        return view('flats.show',compact('flat','pageTitle','house'));
    }
}
