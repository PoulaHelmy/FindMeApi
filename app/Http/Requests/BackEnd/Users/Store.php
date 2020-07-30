<?php

namespace App\Http\Requests\BackEnd\Users;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
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
     * 'name' => 'required|unique:|max:191',

     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone'=>'required|string|min:10|max:15|unique:users,phone',
            'remember_me' => 'boolean'
        ];
    }
}
