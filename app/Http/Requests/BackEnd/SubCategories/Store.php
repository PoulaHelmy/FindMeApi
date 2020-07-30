<?php

namespace App\Http\Requests\BackEnd\SubCategories;

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
            'name' => ['required','min:3', 'max:191','string','unique:subcats'],
            'meta_keywords' => ['max:191'],
            'meta_des' => ['max:191'],
            'category_id'=>['integer','required']
        ];
    }
}
