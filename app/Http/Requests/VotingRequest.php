<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VotingRequest extends FormRequest
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
            'name' => 'required',
            'kind' => 'required',
            'voting_type'=>'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Название',
            'kind' => 'Вид',
            'voting_type'=>'Тип',

        ];
    }
}
