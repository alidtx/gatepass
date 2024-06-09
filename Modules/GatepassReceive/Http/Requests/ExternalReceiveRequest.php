<?php

namespace Modules\GatepassReceive\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ExternalReceiveRequest extends FormRequest
{
    use ApiResponseTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this->id);
        return [
            'to_location_id' => "required|int|exists:locations,id",
            'receive_date_time' => "required|date_format:Y-m-d H:i",
            'party' => "required|string|max:100",
            'receive_no' => "required|unique:external_receives,receive_no,".$this->id,
            'gatepass_no' => "nullable",
            'challan_no' => "nullable",
            'to_person' => "nullable|string|max:100",
            'to_department_id' => "nullable|int|exists:departments,id",
            'note' => "nullable",
            "item_details" => 'array|min:1',
            'item_details.*.item_id' => 'required|int',
            'item_details.*.item_description' => 'nullable|string',
            'item_details.*.unit_id' => 'nullable|int|exists:units,id',
            'item_details.*.document_qty' => 'required|numeric|gt:0',
            'item_details.*.received_qty' => 'required|numeric|gt:0',
            "documents" => 'nullable|array',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator): ValidationException
    {
        throw new HttpResponseException($this->invalidResponse(422, $validator->errors()->all(), $validator->errors()->all()));
    }
}
