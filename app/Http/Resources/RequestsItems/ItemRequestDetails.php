<?php

namespace App\Http\Resources\RequestsItems;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemRequestDetails extends JsonResource
{

    public function toArray($request)
    {
        $AllQuestions=[];
        foreach($this->questionResponses as $question){
            array_push($AllQuestions,$question);
        }
        return [
            'id'            =>$this->id,
            'user_id' =>$this->user_id,
            'name'          =>$this->name,
            'description'   =>$this->des,
            'status'  =>$this->status,
            'item_id' =>$this->item_id,
            'created_at'    =>$this->created_at,
            'AllQuestions'=> $AllQuestions
        ];
    }
}
