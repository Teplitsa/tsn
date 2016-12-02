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
        $companies = Company::orderBy('name')->get()->map(function ($company) {
            return [
                'value' => $company->id,
                'text'  => $company->name,
            ];
        });
        $component = 'attach-flat';
        return view('flats.attach', compact('pageTitle', 'companies', 'component'));
    }

    public function attachHandler(Requests\AttachFlat $request)
    {
        $flat = Flat::where('account_number', $request->input('account'))
            ->whereHas('house', function (Builder $subQuery) use ($request) {
                $subQuery->where('company_id', $request->input('company_id'));
            })->first();

        if ($flat === null) {
            return response()->json(['account' => ['Такой лицевой счет в указанном ТСЖ не найден']], 422);
        }

        $registeredFlat = new RegisteredFlat();
        $registeredFlat->user()->associate(auth()->user());
        $registeredFlat->flat()->associate($flat);
        $registeredFlat->activate_code = str_random(15);
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
        $pageTitle = $flat->address;//////////////////////////?
        $template = 'active';

        $votings = $flat->flat->activeVotings;///////////////////////?

        return view('flats.' . $template, compact('flat', 'pageTitle', 'votings'));

    }

    public function not_active(RegisteredFlat $flat)
    {
        $pageTitle = $flat->address;
        $template = 'not_active';
        $component = 'app-activate-flat';
        return view('flats.' . $template, compact('flat', 'component', 'pageTitle'));

    }

    public function activate(RegisteredFlat $flat, Request $request)
    {
        abort_if(!$flat->isOwned(auth()->user()), 403);

        if ($flat->activate_code !== $request->input('code', '')) {
            return response()->json(['code' => ['Неверный код активации']], 422);
        }

        $flat->active = true;
        $flat->save();
        $this->addToastr('success', 'Квартира успешно активирована. Теперь вам доступны все возможности системы',
            'Квартира активирована');
        return ['redirect' => route('flats.show', $flat)];
    }

    public function success()
    {
        return view('flats.success');
    }
}
