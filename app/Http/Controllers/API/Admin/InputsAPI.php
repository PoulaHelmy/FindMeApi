<?php

namespace App\Http\Controllers\API\Admin;


use App\Http\Controllers\API\ApiHome;
use App\Http\Requests\BackEnd\Inputs\Store;
use App\Http\Requests\BackEnd\Inputs\Update;
use App\Http\Resources\Inputs\InputsFullDetailsResource;
use App\Http\Resources\Inputs\InputsResource;
use App\Models\Input;
use App\Models\inputOption;
use App\Models\inputValidator;
use Illuminate\Http\Request;

class InputsAPI extends ApiHome
{
    public function __construct(Input $model)
    {
        parent::__construct($model);
    }//end of constructor

    public function index(Request $request)
    {
        return InputsResource::collection(Input::all());
    }//end of index

    //To Show All Inputs Details Data
    public function show($id)
    {

        $row = $this->model->findOrFail($id);
        if ($row) {
            return $this->sendResponse(new InputsFullDetailsResource($row),
                'Data Retrieved Successfully');
        }
        return $this->sendError('Not Found', 400);
    }//end of show

    public function indexWithFilter(Request $request)
    {
        if ($request->get('filter') == '' || $request->get('filter') == null) {
            return InputsResource::collection(
                Input::orderBy($request->get('order'), $request->get('sort'))->
                paginate($request->get('pageSize')));
        } else {
            return
                InputsResource::collection(Input::when($request->filter, function ($query) use ($request) {
                    return $query->where('name', 'like', '%' . $request->filter . '%');
                })
                    ->orderBy($request->get('order'), $request->get('sort'))
                    ->paginate($request->get('pageSize')));
        }
    }//endof index

    public function store(Store $request)
    {
        $inputsData = [
            'name' => $request->name,
            'label' => $request->label,
            'type' => $request->type,
            'placeholder' => $request->placeholder ? $request->placeholder : '',
            'inputOptionsInput' => $request->inputOptionsInput ? $request->inputOptionsInput : '',
        ];
        $row = Input::create($inputsData);
        $inputsOtpions = $request->get('inputOptions');
        if (isset($inputsOtpions) && sizeof($inputsOtpions) > 0) {
            for ($i = 0; $i < sizeof($inputsOtpions); $i++) {
                $option = [
                    'input_id' => $row->id,
                    'optionName' => $request->inputOptions[$i]['optionName']
                ];
                $option = inputOption::create($option);
            }
        }
        $inputsValidators = $request->get('inputsValidators');
        if (isset($inputsValidators) && sizeof($inputsValidators) > 0) {
            for ($i = 0; $i < sizeof($inputsValidators); $i++) {
                $validator = [
                    'input_id' => $row->id,
                    'validatorName' => $inputsValidators[$i]['validatorName'],
                    'validatorMessage' => $inputsValidators[$i]['validatorMessage'],
                    'validatorOptions' => $inputsValidators[$i]['validatorOptions'],
                ];
                $validator = inputValidator::create($validator);
            }
        }
        return $this->sendResponse(new InputsResource($row), 'Created Successfully');
    }//end of store

    public function update(Update $request, $id)
    {
        $row = $this->model->find($id);
        if (!$row)
            return $this->sendError('This Input Not Found', 400);
        foreach ($row->optionsValidators as $valid) {
            $deleted = inputValidator::find($valid->id);
            $deleted->delete();
        }
        foreach ($row->optionsInputs as $option) {
            $deleted = inputOption::find($option->id);
            $deleted->delete();
        }
        $inputsData = [
            'label' => $request->label,
            'type' => $request->type,
            'placeholder' => $request->placeholder ? $request->placeholder : '',
            'inputOptionsInput' => $request->inputOptionsInput ? $request->inputOptionsInput : '',
        ];
        if ($request->name !== $row->name) {
            $inputsData['name'] = $request->name;
        }
        $row->update($inputsData);
        $inputsOtpions = $request->get('inputOptions');
        if (isset($inputsOtpions) && sizeof($inputsOtpions) > 0) {

            for ($i = 0; $i < sizeof($inputsOtpions); $i++) {

                $option = [
                    'input_id' => $row->id,
                    'optionName' => $request->inputOptions[$i]['optionName']
                ];
                $option = inputOption::create($option);
            }
        }
        $inputsValidators = $request->get('inputsValidators');
        if (isset($inputsValidators) && sizeof($inputsValidators) > 0) {
            for ($i = 0; $i < sizeof($inputsValidators); $i++) {
                $validator = [
                    'input_id' => $row->id,
                    'validatorName' => $inputsValidators[$i]['validatorName'],
                    'validatorMessage' => $inputsValidators[$i]['validatorMessage'],
                    'validatorOptions' => $inputsValidators[$i]['validatorOptions'],
                ];
                $validator = inputValidator::create($validator);
            }
        }
        return $this->sendResponse('', 'Input Updated Successfully');
    }//end of update
}//end of Class
