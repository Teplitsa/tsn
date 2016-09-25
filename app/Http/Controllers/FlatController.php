<?php

namespace App\Http\Controllers;

use App\Company;
use App\Flat;
use App\RegisteredFlat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

use App\Http\Requests;

class FlatController extends Controller
{
    public function attach()
    {
        $pageTitle = 'Подключение квартиры';
        $companies = Company::orderBy('name')->get();
        $component = 'attach-flat';
        return view('flats.attach', compact('pageTitle', 'companies', 'component'));
    }

    public function attachHandler(Requests\AttachFlat $request)
    {
        $flat = Flat::where('account_number', $request)
            ->whereHas('house', function (Builder $subQuery) use ($request) {
                $subQuery->where('company_id', $request->input('company_id'));
            })->first();

        if ($flat === null) {
            return response()->json(['account' => ['Такой лицевой счет в указанном ТСЖ не найден']], 422);
        }

        $registeredFlat = new RegisteredFlat();
        $registeredFlat->user()->associate(auth()->user());
        $registeredFlat->flat()->associate($flat);
    }
}
