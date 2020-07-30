<?php

namespace App\Http\Controllers\API;

use App\Events\RequestStatus;
use App\Http\Controllers\API\ApiHome;

use App\Http\Requests\BackEnd\Requests\Store;
use App\Http\Requests\BackEnd\Requests\Update;
use App\Http\Resources\RequestsItems\ItemRequest;
use App\Http\Resources\RequestsItems\ItemRequestDetails;
use App\Models\Chat;
use App\Models\Item;
use App\Models\QuestionResponse;
use App\Models\RequestItems;
use App\Models\User;
use App\Notifications\CreateRequest;
use App\Notifications\RequestChangeStatus;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;


class ItemsRequests extends ApiHome
{
    public function __construct(RequestItems $model){
        parent::__construct($model);
    }//end of constructor
    public function index(Request $request){
        return ItemRequest::collection(
            $this->model->where('user_id','=',auth()->user()->id)->get());
    }//end of index
    public function show($id){
        $row=$this->model->findOrFail($id);
        if($row) {
            return $this->sendResponse(new ItemRequestDetails($row),
                'Data Retrieved Successfully');
        }
        return $this->sendError('Not Found',400);
    }
    public function store(Store $request)
    {
        $item_id=$request->item_id;
        //to make sure that user can't create more than request for one item
        $reqNum=User::find(auth()->user()->id)->itemRequests()->where(
            'item_id','=',$item_id
        )->count();
        if($reqNum>0){
            return $this->sendError('You Already Requested this Item So You can not make Request again ',404);
        }
        //to make sure that user can't create request for their item's
        $userCheck=Item::find($item_id)->user->id;
        if(auth()->user()->id===$userCheck){
            return $this->sendError('This Item IS Uploaded By You So you can nor Request It',404);
        }
        $requestArray=[
            'user_id' => auth()->user()->id,
            'item_id'=>$request->item_id,
            'name'=>$request->name,
            'des'=>$request->des
        ];
        $row = $this->model->create($requestArray);
        for($i=0;$i<sizeof($request->questions);$i++){
            $questionRes=[
                'question'=>$request->questions[$i]['question'],
                'answer'=>$request->questions[$i]['answer'],
                'request_id'=>$row->id,
            ];
            $rowData=QuestionResponse::create($questionRes);
        }
        $item=Item::find($item_id);
        $user=User::find($item->user_id);
        $user->notify(new CreateRequest($user->id,$row->id));
        return $this->sendResponse($row,
            'Request Created Successfully');
    }
    public function update(Update $request,$id){
        $row=$this->model->find($id);
        if(!$row)
            return $this->sendError('This Request Not Found',404);
        $row->name=$request->get('name');
        $row->des=$request->get('des');
        $row->save();
        foreach ($row->questionResponses as $quesRes){
            $quesResData=QuestionResponse::find($quesRes->id);
            $quesResData->delete();
        }
        for($i=0;$i<sizeof($request->questions);$i++){
            $questionRes=[
                'question'=>$request->questions[$i]['question'],
                'answer'=>$request->questions[$i]['answer'],
                'request_id'=>$row->id,
            ];
            $rowData=QuestionResponse::create($questionRes);
        }
        return $this->sendResponse('',
            'Request Updated Successfully');
    }
    public function destroy($id){
        $row=$this->model->find($id);
        if(!$row)
            return $this->sendError('This Request Not Found',400);
        $row->delete();
        return$this->sendResponse(null,'ITEM Deleted Successfully');
    }//end of destroy
    public function changeStatus(Request $request){
        $v = validator($request->all(), [
            'req_id' => 'integer',
            'status' => ['integer'],
        ]);
        if ($v->fails())
            return $this->sendError('Validation Error.!',$v->errors()->all(),400);
        $row=$this->model->find($request->req_id);
        if (!$row)
            return $this->sendError('this Request Not Found',400);
        $row->status=$request->status;
        $row->save();
        $reqq=$this->model->find($request->req_id);
        if($request->status===1){
            $chat_id=Chat::create(['user_1'=>auth()->user()->id,
                'user_2'=>$reqq->user_id,'request_id'=>$reqq->id]);
        }
        $user=User::find($reqq->user_id);
        $user->notify(new RequestChangeStatus($user->name,$request->status,$row->name,$row->id));
        return $this->sendResponse(new ItemRequest($row),
            'Request Updated Successfully');

    }
    public function indexWithFilter(Request $request){
        if($request->get('filter')==''||$request->get('filter')==null){
            return ItemRequest::collection(
                RequestItems::orderBy($request->get('order'), $request->get('sort'))->
                paginate($request->get('pageSize')));
        }
        else{
            return
                ItemRequest::collection(RequestItems::when($request->filter,function ($query)use($request){
                    return $query->where('name','like','%'.$request->filter.'%');})
                    ->orderBy($request->get('order'), $request->get('sort'))
                    ->paginate($request->get('pageSize')));
        }
    }//end of indexWithFilter
    public function incoming_requests(Request $request){
        $requests=[];
        foreach (auth()->user()->items as $item){
            foreach ($item->itemRequests as $reqItem){
                if($reqItem->status!=-1){
                    array_push($requests,new ItemRequestDetails($reqItem));
                }
            }
        }
        return $this->sendResponse($requests,
            'Data Retrieved Successfully');

    }//end of incoming_requests

}//end of Class


























//public function incoming_requests(Request $request){
//    $v = validator($request->all(), [
//        'user_id' => 'integer',
//    ]);
//    if ($v->fails())
//        return $this->sendError('Validation Error.!',$v->errors()->all(),400);
//    $items=Item::where('user_id',$request->user_id)->get();
//    $requests=[];
//    foreach ($items as $item){
//        foreach ($item->itemRequests as $reqItem){
//            array_push($requests,$reqItem);
//        }
//    }
//    return $this->sendResponse($requests,
//        'Data Retrieved Successfully');
//
//}//end of incoming_requests

//
//public function incoming_requests(Request $request){
//    $requests=[];
//    foreach (auth()->user()->items as $item){
//        foreach ($item->itemRequests as $reqItem){
//            array_push($requests,new ItemRequestDetails($reqItem));
//        }
//    }
//    return $this->sendResponse($requests,
//        'Data Retrieved Successfully');
//
//}//end of incoming_requests
