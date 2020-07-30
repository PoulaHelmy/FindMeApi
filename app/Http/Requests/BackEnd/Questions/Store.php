<?php

namespace App\Http\Requests\BackEnd\Questions;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'questions.*.name' => ['required','min:3', 'max:191','string'],
            'item_id' => ['required','integer'],
        ];
    }
}
