<?php

namespace App\Http\Controllers;

use App\City;
use App\Company;
use App\Flat;
use App\House;
use App\Models\User;
use App\Notifications\ActivateFlat;
use App\Notifications\NewFlat;
use App\RegisteredFlat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Notifications\Messages\MailMessage;

class FlatController extends Controller
{
    public function attach()
    {
        $pageTitle = 'Подключение квартиры';

        $cities = City::orderBy('name')->get()->map(
            function ($city) {
                return [
                    'value' => $city->id,
                    'text' => $city->name,
                ];
            }
        );


        $component = 'attach-flat';

        return view('flats.attach', compact('pageTitle', 'component', 'cities'));
    }

    public function edit(RegisteredFlat $flat)
    {
        $pageTitle = 'Редактирование квартиры';
        $cities = City::orderBy('name')->get()->map(
            function ($city) {
                return [
                    'value' => $city->id,
                    'text' => $city->name,
                ];
            }
        );
        $component = 'update-flat';
        $currentFlat=$flat->id;

        return view('flats.edit', compact('currentFlat','pageTitle', 'component', 'cities', 'flat'));
    }

    public function update(RegisteredFlat $flat, Requests\UpdateFlatRequest $request)
    {
        $registeredFlat = $flat;
        $flat = Flat::inTheHouse($request->input('city'), $request->input('street_id'), $request->input('number'))
            ->where('number', $request->input('flat'))
            ->first();

        if ($flat === null) {
            return response()->json(['flat' => ['Такой квартиры не существует']], 422);
        }
        $registeredFlat->flat()->associate($flat);
        $registeredFlat->fill(
            $request->only(['square', 'up_part', 'down_part', 'number_doc', 'issuer_doc'])
        );
        $registeredFlat->user_share = $registeredFlat->square / $registeredFlat->down_part * $registeredFlat->up_part;
        $registeredFlat->date_doc = Carbon::createFromFormat('d.m.Y', $request->date_doc);
        if ($request->file('scan')) {
            $this->validate(
                $request,
                [
                    'scan' => 'image',
                ],
                [],
                [
                    'old_password' => 'Скан документа о праве собственнсоти',
                ]
            );
            $registeredFlat->scan = $request->scan->store('scans_of_documents');
        }
        if ($registeredFlat->wasImportantChanged()) {
            $registeredFlat->active = 0;
            $registeredFlat->save();
            
        }

        return ['redirect' => route('flats.show', $registeredFlat)];
    }

    public function attachHandler(Requests\AttachFlat $request)
    {
        $flat = Flat::inTheHouse($request->input('city'), $request->input('street_id'), $request->input('number'))
            ->where('number', $request->input('flat'))
            ->first();
        $house=House::where('street_id',$request->input('street_id'))
            ->where('number',$request->input('number'))
            ->first();

        if ($house===null){
            return response()->json(['number' => ['Дом не прикреплен']], 422);
        }
        if ($flat === null) {
            return response()->json(['flat' => ['Такой квартиры не существует']], 422);
        }

        $registeredFlat = new RegisteredFlat();
        $registeredFlat->user()->associate(auth()->user());
        $registeredFlat->flat()->associate($flat);
        $registeredFlat->fill(
            $request->only(['square', 'up_part', 'down_part', 'number_doc', 'issuer_doc'])
        );
        $registeredFlat->user_share = $registeredFlat->square / $registeredFlat->down_part * $registeredFlat->up_part;
        $registeredFlat->date_doc = Carbon::createFromFormat('d.m.Y', $request->date_doc);
        $registeredFlat->scan = $request->scan->store('scans_of_documents');
        $company=$house->company;
        User::where('company_id',$company->id)->each(function ($user) use ($registeredFlat){
            \Notification::send($user, new NewFlat($registeredFlat));
        });
        $registeredFlat->active = 0;
        $registeredFlat->save();

        return ['redirect' => route('flats.show', $registeredFlat)];
    }

    public function show(RegisteredFlat $flat)
    {
        abort_if(!$flat->isOwned(auth()->user()), 403);

        view()->share('currentFlat', $flat->id);
        if (!$flat->active) {
            return $this->not_active($flat);
        }

        return $this->active($flat);
    }

    public function active(RegisteredFlat $flat)
    {
        $pageTitle = $flat->address;
        $template = 'active';

        $votings = $flat->flat->activeVotings;
        $currentFlat=$flat->id;
        return view('flats.'.$template, compact('currentFlat','flat', 'pageTitle', 'votings'));

    }

    public function not_active(RegisteredFlat $flat)
    {
        $pageTitle = $flat->address;
        $template = 'not_active';
        $component = 'app-activate-flat';

        return view('flats.'.$template, compact('flat', 'component', 'pageTitle'));

    }

    public function activate(RegisteredFlat $flat, Request $request)
    {
        abort_if(!$flat->isOwned(auth()->user()), 403);

        if ($flat->activate_code !== $request->input('code', '')) {
            return response()->json(['code' => ['Неверный код активации']], 422);
        }

        $flat->active = true;

        $flat->save();
        $this->addToastr(
            'success',
            'Квартира успешно активирована. Теперь вам доступны все возможности системы',
            'Квартира активирована'
        );

        return ['redirect' => route('flats.show', $flat)];
    }

    public function success()
    {
        return view('flats.success');
    }
}
