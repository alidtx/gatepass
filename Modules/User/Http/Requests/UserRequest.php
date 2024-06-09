<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'name' => "required",
            'email' => "required|unique:users,email,".$this->id,
            'phone' => "nullable",
            "department_id" => "required|exists:departments,id",
            "employee_id" => "nullable|string",
            "user_status" => "required|in:0,1",
            "role_id" => "required|array",
            "role_id.*" => "int|exists:roles,id"
        ];

        // Check if the request method is PUT or PATCH (update action)
        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            // Exclude password field from validation
            unset($rules['password']);
        } else {
            // For other methods (like POST), validate the password field
            $rules['password'] = "required|string|min:4|confirmed";
        }

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
