<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'name'  => ['sometimes', 'required', 'string', 'min:2', 'max:255'],
            'phone' => [
                'sometimes',
                'nullable',
                'string',
                // Strict Egyptian Mobile Number Validation
                'regex:/^01[0125][0-9]{8}$/',
                Rule::unique('users', 'phone')->ignore($this->user()?->id),
            ],
            'photo' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    /**
     * Custom error messages for ALL specific validation rules.
     */
    public function messages(): array
    {
        return [
            // Name Messages
            'name.required' => 'Please provide your full name.',
            'name.string'   => 'The name must be valid text.',
            'name.min'      => 'Your name must be at least 2 characters long.',
            'name.max'      => 'Your name cannot exceed 255 characters.',

            // Phone Messages
            'phone.string'  => 'The phone number format is invalid.',
            'phone.regex'   => 'Please provide a valid 11-digit Egyptian mobile number (e.g., 010..., 011..., 012..., 015...).',
            'phone.unique'  => 'This phone number is already registered to another account.',

            // Photo Messages
            'photo.image'   => 'The uploaded file must be a valid image format.',
            'photo.mimes'   => 'The profile photo must be a file of type: jpeg, png, jpg, webp.',
            'photo.max'     => 'The profile photo must not be larger than 2MB.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'  => false,
            'message' => 'Validation failed. Please check your inputs.',
            'errors'  => $validator->errors()
        ], 422));
    }
}
