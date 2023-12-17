<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
     public function rules()
    {
        return [
            'avatar' => 'mimes:jpg,jpeg,png',
            'login' => 'unique:users|regex:/^[a-zA-Z]+$/u',
        ];
    }

    public function messages()
    {
        return [
            'avatar.mimes' => 'Изображения могут быть только jpg, jpeg, png форматов',
            'login.unique' => 'Этот логин уже занят',
            'login.regex' => 'Логин может быть только латиницей',
        ];
    }
}
