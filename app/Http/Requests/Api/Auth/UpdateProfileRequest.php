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
                Rule::unique('users', 'phone')->ignore($this->user()?->id),

                // ✨ Professional Smart Phone Validation ✨
                function ($attribute, $value, $fail) {
                    if (!$value) return; // Skip if null

                    // 1. Egypt (+20): Must be exactly 11 digits after country code, starting with 10, 11, 12, or 15
                    if (str_starts_with($value, '+20')) {
                        if (!preg_match('/^\+201[0125]\d{8}$/', $value)) {
                            $fail('Please provide a valid Egyptian mobile number (e.g., +2010..., +2011...).');
                        }
                    }
                    // 2. Saudi Arabia (+966): Must start with 5 and have 9 digits after country code
                    elseif (str_starts_with($value, '+966')) {
                        if (!preg_match('/^\+9665\d{8}$/', $value)) {
                            $fail('Please provide a valid Saudi mobile number (e.g., +9665...).');
                        }
                    }
                    // 3. UAE (+971): Must start with 5 and have 9 digits after country code
                    elseif (str_starts_with($value, '+971')) {
                        if (!preg_match('/^\+9715\d{8}$/', $value)) {
                            $fail('Please provide a valid UAE mobile number (e.g., +9715...).');
                        }
                    }
                    // 4. Kuwait (+965): Must start with 5, 6, or 9 and have 8 digits after country code
                    elseif (str_starts_with($value, '+965')) {
                        if (!preg_match('/^\+965[569]\d{7}$/', $value)) {
                            $fail('Please provide a valid Kuwait mobile number (e.g., +9655...).');
                        }
                    }
                    // 5. Generic Fallback for all other countries (E.164 Standard)
                    else {
                        if (!preg_match('/^\+[1-9]\d{7,14}$/', $value)) {
                            $fail('Please provide a valid international phone number starting with the country code.');
                        }
                    }
                },
            ],
            'photo' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // Max 5MB
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
            'phone.unique'  => 'This phone number is already registered to another account.',
            // Note: The specific format error messages are handled directly inside the closure above.

            // Photo Messages
            'photo.image'   => 'The uploaded file must be a valid image format.',
            'photo.mimes'   => 'The profile photo must be a file of type: jpeg, png, jpg, webp.',
            'photo.max'     => 'The profile photo must not be larger than 5MB.',
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
