<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateLocationRequest extends FormRequest
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
            // Latitude must be between the South Pole (-90) and North Pole (90)
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            // Longitude must be between the Western Hemisphere (-180) and Eastern Hemisphere (180)
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ];
    }

    /**
     * Custom error messages for specific validation rules.
     */
    public function messages(): array
    {
        return [
            'latitude.required'  => 'The latitude coordinate is required.',
            'latitude.numeric'   => 'The latitude must be a valid number.',
            'latitude.between'   => 'The latitude must be a valid geographical coordinate between -90 and 90.',

            'longitude.required' => 'The longitude coordinate is required.',
            'longitude.numeric'  => 'The longitude must be a valid number.',
            'longitude.between'  => 'The longitude must be a valid geographical coordinate between -180 and 180.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'  => false,
            'message' => 'Invalid location coordinates. Please check your inputs.',
            'errors'  => $validator->errors()
        ], 422));
    }
}
