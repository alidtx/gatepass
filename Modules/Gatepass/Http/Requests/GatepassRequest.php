<?php

namespace Modules\Gatepass\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;


class GatepassRequest extends FormRequest
{
    use ApiResponseTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'type' => "required|int",
            "to_location" => "required_if:type,1|exists:locations,id",
            'party' => "required_if:type,2,3|exists:parties,id",
            "creation_date_time" => "required|date_format:Y-m-d H:i",
            "challan_no" => "nullable|unique:gatepasses,challan_no,".$this->id,
            "creation_user" => "required|exists:users,id",
            "department" => "nullable|exists:departments,id",
            "note" => "nullable",
            "to_person" => "nullable",
            "to_department" => "nullable",
            "external_to_person" => "nullable",
            "mobile_no" => "nullable",
            "purpose" => "nullable",
            "vehicle_no" => "nullable",
            "from_location" => "nullable",
            "carrying_person" => "nullable",
            "challan_no_party" => "nullable",
            "item_details" => 'array|min:1',
            'item_details.*.id' => 'required|int',
            'item_details.*.item_description' => 'nullable|string',
            'item_details.*.unit_id' => 'nullable|int|exists:units,id',
            'item_details.*.qty' => 'required|int|min:1',
            "documents" => 'nullable|array',
        ];

        // Apply 'required' rule for 'gate_pass_no' only for update method
        // if ($this->id) {
        //     $rules['gate_pass_no'] = 'required';
        // }

        return $rules;
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
