<?php

namespace App\Http\Resources\Inputs;

use Illuminate\Http\Resources\Json\JsonResource;

class InputsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'type' => $this->type,
            'placeholder' => $this->placeholder,
            'inputType' => $this->inputType,
            'created_at' => $this->created_at
        ];

    }
}
