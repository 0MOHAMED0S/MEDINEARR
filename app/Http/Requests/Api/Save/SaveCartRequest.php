<?php

namespace App\Http\Requests\Api\Save;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // ✨ مسموح للمستخدم المصادق عليه
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'medicine_id' => 'required|integer|exists:medicines,id',
            'pharmacy_id' => 'required|integer|exists:pharmacies,id',
            'quantity'    => 'nullable|integer|min:1'
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

            'quantity.integer'     => 'The quantity must be a valid number.',
            'quantity.min'         => 'The quantity cannot be less than 1.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     * ✨ توحيد شكل الاستجابة للأخطاء ليتطابق مع باقي الـ API ✨
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
