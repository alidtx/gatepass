<?php

namespace Modules\GatepassReceive\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class InternalReceiveRequest extends FormRequest
{
    use ApiResponseTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to_location_id' => "required|int|exists:locations,id",
            'gatepass_check_id' => "required|int|exists:gatechecks,id|unique:internal_receives,gatepass_check_id,".$this->id,
            'received_date_time' => "required|date_format:Y-m-d H:i",
            "item_details" => 'array|min:1',
            'item_details.*.item_id' => 'required|int',
            'item_details.*.released_qty' => 'required',
            'item_details.*.received_qty' => 'required|numeric|min:1',
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
