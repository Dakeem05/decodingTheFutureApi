<?php

namespace App\Http\Requests\Api\V1;

use App\Models\EventRegistration;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
      
    public function rules(): array
    {
        return [
            'name' => ['required', 'string',],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:'.EventRegistration::class],
            'phone' => ['required', 'digits:10', 'unique:'.EventRegistration::class],
        ];
    }

    public function failedValidation (Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ], 400));
    }
}
