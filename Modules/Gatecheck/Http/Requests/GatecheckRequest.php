<?php

namespace Modules\Gatecheck\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class GatecheckRequest extends FormRequest
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
            'from_location_id' => "required|int|exists:locations,id",
            "released_by" => "required|exists:users,id",
            "release_date_time" => "nullable|date_format:Y-m-d H:i",
            'gatepass_id' => "required|int|unique:gatechecks,gatepass_id",
            "created_by" => "required|exists:users,id"
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
}
