<?php

namespace App\Http\Requests\BackEnd\Items;

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
            'name'=> ['required','min:3', 'max:191','string'],
            'category_id'=>['required','integer'],
            'subcat_id'=>['required','integer'],
            'location'=>['required','min:3','string'],
            'lat'       =>['required'],
            'lan' =>['required'],
            'des'=>['required','min:3','string'],
            'is_found'=>['required','integer'],
            'date'=>['date','required'],

           'images.*.*'=>[
            //    'image',
            //    'mimes:jpeg,bmp,png'
               ]

        ];
    }
}
