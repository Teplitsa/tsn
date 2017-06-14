<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachFlat extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'city'       => 'required|exists:cities,id',
            'street_id'  => [
                'required',
                Rule::exists('streets', 'id')->where(
                    function ($query) {
                        $query->where('city_id', $this->city);
                    }
                ),
            ],
            'number'     => 'required',
            'flat'       => 'required',
            'square'     => 'numeric|required|min:0.001',
            'up_part'    => 'required|min:1',
            'down_part'  => 'required',
            'number_doc' => 'required',
            'date_doc'   => 'required|date_format:d.m.Y',
            'issuer_doc' => 'required',
            'scan'       => 'required|image',

        ];
    }

    public function attributes()
    {
        return [
            'city'       => 'Город',
            'street_id'  => 'Улица',
            'number'     => 'Номер дома',
            'flat'       => 'Номер квартиры',
            'square'     => 'Площадь',
            'up_part'    => 'Числитель дроби',
            'down_part'  => 'Знаменатель дроби',
            'number_doc' => 'Номер документа о праве собственности',
            'date_doc'   => 'Дата выдача документа о праве собственности',
            'issuer_doc' => 'Орган, выдавший документ о праве собственнсоти',
            'scan'       => 'Скан документа о праве собственнсоти',
        ];
    }
}
