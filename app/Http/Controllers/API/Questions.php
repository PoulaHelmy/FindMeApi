<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\ApiHome;
use App\Http\Requests\BackEnd\Questions\Store;
use App\Models\Item;
use App\Models\Question;
use Illuminate\Http\Request;

class Questions  extends ApiHome
{
    public function __construct(Question $model){
          parent::__construct($model);
    }//end of constructor

    public function show($id){
        $row=Item::find($id);
        $AllQuestions=[];
        if(!$row)
            return $this->sendError('This ITEM Not Found',400);

        foreach ($row->questions as $questionItem){
            $question=Question::find($questionItem->id);
            array_push($AllQuestions,$question);
        }
        return $this->sendResponse($AllQuestions,'All Questions Of This Item ');
    }

    public function store(Store $request){
        $item_id=$request->get('item_id');
        $requestArray = $request->get('questions');
        for($i = 0 ;$i< sizeof($requestArray) ;$i++){
            $question=[
                'item_id'=>$item_id,
                'name'=>$requestArray[$i]['name']
            ];
            $question=Question::create($question);
    }

        return $this->sendResponse('','Questions Attached Successfully');
    }//end of store

    public function update(Store $request,$id){

        $item_id=$request->get('item_id');
        $requestArray = $request->get('questions');
        $row=Item::find($item_id);
        foreach ($row->questions as $questionItem){
            $question=Question::find($questionItem->id);
            $question->delete();
        }
        for($i = 0 ;$i< sizeof($requestArray) ;$i++){
            $question=[
                'item_id'=>$item_id,
                'name'=>$requestArray[$i]['name']
            ];
            $question=Question::create($question);
        }

        return $this->sendResponse('','Questions Updated Successfully');
    }//end of update


















}//end of Class
