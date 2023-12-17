<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class registerRequest extends FormRequest
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
            'login' => 'required|unique:users|regex:/^[a-zA-Z]+$/u',
            'password' => 'required|min:8',
            'avatar' => 'mimes:jpg,jpeg,png',
            'email' => 'unique:user_infos'
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Логин обязателен',
            'login.unique' => 'Этот логин уже занят',
            'email.unique' => 'Эта почта уже занята',
            'login.regex' => 'Логин может быть только латиницей',
            'password.required' => 'Пароль обязателен',
            'password.min' => 'Пароль не менее 8 символов',
            'avatar.mimes' => 'Изображения могут быть только jpg, jpeg, png форматов',
        ];
    }
}
