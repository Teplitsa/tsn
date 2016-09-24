<?php

namespace App\Http\Controllers;

use App\Dictionary;
use App\DictionaryValue;
use App\Transformers\Dictionary\All;
use Illuminate\Http\Request;

use App\Http\Requests;

class Dictionaries extends Controller
{
    public function __construct()
    {
        view()->share('active_dictionary', 'active');
    }

    public function index()
    {
        $dictionaries = Dictionary::all();
        $dictionaries = fractal()
            ->collection($dictionaries)
            ->transformWith(new All())
            ->toArray();

        $pageTitle = 'Словари';
        $component = 'app-dictionary';
        return view('dictionaries.index', compact('dictionaries', 'component', 'pageTitle'));
    }

    public function save(Requests\DictionaryManage $request)
    {
        $dictionaries = Dictionary::all()->keyBy('id');
        $ids = $dictionaries->pluck('id');
        $savedDictionaries = collect($request->input('dictionary', []));
        $savedIds = $savedDictionaries->pluck('id');
        $idsToRemove = $ids->diff($savedIds);

        \Log::info($savedDictionaries);

        $savedDictionaries->each(function ($dictionary) use (&$dictionaries) {

            if ($dictionary['id'] == null) {
                $DBdictionary = new Dictionary();
                $DBdictionary->keyword = array_get($dictionary, 'keyword');
            } else {
                $DBdictionary = $dictionaries->get($dictionary['id'], null);
                if (null == $DBdictionary) {
                    return;
                }
            }
            //unset($dictionary['id']);
            $DBdictionary->name = $dictionary['name'];
            $DBdictionary->save();
            $DBvalues = $DBdictionary->values->keyBy('id');
            $values = collect($dictionary['items']);
            $DBvaluesId = $DBvalues->pluck('id');
            $valuesId = $values->pluck('id');
            $valuesToRemove = $DBvaluesId->diff($valuesId);
            $values->each(function ($val) use (&$DBvalues, &$DBdictionary) {
                if ($val['id'] == null) {
                    $DBval = new DictionaryValue();
                    $DBval->dictionary()->associate($DBdictionary);
                } else {
                    $DBval = $DBvalues->get($val['id'], null);
                    if (null == $DBval) {
                        return;
                    }
                }
                unset($val['id']);
                $DBval->fill($val);
                $DBval->save();
            });


            $valuesToRemove->each(function ($val) use (&$DBvalues) {
                $DBVal = $DBvalues->get($val, null);

                if (null == $DBVal) {
                    return;
                }
                $DBVal->delete();
            });
        });
        $idsToRemove->each(function ($dictionary) use (&$dictionaries) {
            $DBdictionary = $dictionaries->get($dictionary, null);
            if ($DBdictionary == null) {
                return;
            }
            $DBdictionary->delete();
        });


        return [
            'data' => [
                'redirect' => route('dictionary.index'),
            ],
        ];
    }
}
