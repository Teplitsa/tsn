<?php

namespace App\Http\Controllers;

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
        $flats=[];
        if (auth()->user()->isUser()){
            $flats=auth()->user()->registeredFlats;
         }
         
        return view('home', ['pageTitle'=>'Главная','flats'=>$flats]);
    }
}
