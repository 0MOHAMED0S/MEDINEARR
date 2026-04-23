<?php

namespace App\Http\Requests\Api\Save;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveMedicinesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // ✨ يجب أن يكون true
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'medicine_id' => 'required|integer|exists:medicines,id',
            'pharmacy_id' => 'required|integer|exists:pharmacies,id',
        ];
    }

    /**
     * Custom error messages for specific validation rules.
     */
    public function messages(): array
    {
        return [
            'medicine_id.required' => 'Please provide a medicine ID.',
            'medicine_id.integer'  => 'The medicine ID must be a valid number.',
            'medicine_id.exists'   => 'The selected medicine does not exist.',

            'pharmacy_id.required' => 'Please provide a pharmacy ID.',
            'pharmacy_id.integer'  => 'The pharmacy ID must be a valid number.',
            'pharmacy_id.exists'   => 'The selected pharmacy does not exist.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     * توحيد شكل الاستجابة للأخطاء
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'data'    => null
        ], 422));
    }
}
