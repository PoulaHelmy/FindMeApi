<?php

namespace App\Http\Requests\BackEnd\Items;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
            'name'=> ['min:3', 'max:191','string'],
            'category_id'=>['integer'],
            'subcat_id'=>['integer'],
            'location'=>['min:3','string'],
            'lat'       =>[],
            'lan' =>[],
            'des'=>['min:3','string'],
            'is_found'=>['integer'],
            'date'=>['date'],
           'images'=>[
            //    'image','mimes:jpeg,bmp,png'
               ]
        ];
    }
}
