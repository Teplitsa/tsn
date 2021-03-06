<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserManage extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'confirmed|min:6',
            'email' => 'email|required',
            'city' => 'alpha-dash',
        ];
    }
    public function attributes()
    {
        return [
            'first_name' => 'Имя',
            'last_name' => 'Отчество',
            'password' => 'Пароль',
            'email' => 'Электронная почта',
            'city' => 'город',
        ];
    }
}
