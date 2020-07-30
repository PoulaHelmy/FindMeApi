<?php


namespace App\Http\Controllers\API;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
class ApiHome extends Controller
{
    protected $model;
    public function __construct(Model $model){
        return $this->model=$model;
    }//end of constructor

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }//end of sendresponse

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }//end of senderrors

    public function index(Request $request){
        $rows=$this->model->latest()->paginate(10);
        if($rows->count()>=0)
            return $this->sendResponse($rows,'Success');
        return $this->sendError('SomeThig Wrong',400);
    }//end of index

    public function show($id){
        $row=$this->model->find($id);
        if($row)
            return $this->sendResponse($row,'Success Retrieve Item ');
        return $this->sendError('Not Found',400);
    }

    public function destroy($id){
        $row=$this->model->find($id);
        if(!$row)
            return $this->sendError('This ITEM Not Found',400);
        $row->delete();
        return$this->sendResponse(null,'ITEM Deleted Successfully');
    }//end of destroy

}//end of controller
