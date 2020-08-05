<?php

namespace App\Http\Resources\Inputs;

use Illuminate\Http\Resources\Json\JsonResource;

class InputsFullDetailsResource extends JsonResource
{

    public function toArray($request)
    {
        $AllValidators = [];
        foreach ($this->optionsValidators as $valid) {
            array_push($AllValidators, $valid);
        }
        $returnedData = [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'type' => $this->type,
            'placeholder' => $this->placeholder,
            'AllValidators' => $AllValidators,
            'created_at' => $this->created_at
        ];
        if ($this->type === 'select' ||
            $this->type === 'checkbox' ||
            $this->type === 'radiobutton') {
            $AllOptions = [];
            foreach ($this->optionsInputs as $option) {
                array_push($AllOptions, $option);
            }
            $returnedData['AllOptions'] = $AllOptions;
            return $returnedData;
        } else {
            $returnedData['inputType'] = $this->inputOptionsInput;
            return $returnedData;
        }
        return $returnedData;
    }
}
