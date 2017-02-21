<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewHouse extends FormRequest
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
            'number_of_flats' => 'required',
            'number' => 'required',
            'flats' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'number_of_flats' => 'Количество квартир',
            'address' => 'Адрес',
            'number' => 'Номер дома',
        ];
    }
}
