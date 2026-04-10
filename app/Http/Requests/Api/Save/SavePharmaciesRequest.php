<?php

namespace App\Http\Requests\Api\Save;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SavePharmaciesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // ✨ يجب أن تكون true ليتمكن المستخدمون من المرور
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'pharmacy_id' => 'required|integer|exists:pharmacies,id',
        ];
    }

    /**
     * Custom error messages for specific validation rules.
     */
    public function messages(): array
    {
        return [
            'pharmacy_id.required' => 'Please provide a pharmacy ID.',
            'pharmacy_id.integer'  => 'The pharmacy ID must be an integer.',
            'pharmacy_id.exists'   => 'The selected pharmacy does not exist.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     * إرجاع الخطأ بنفس الهيكل الثابت للـ API الخاص بك
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(), // جلب أول خطأ فقط لتسهيل العرض في الموبايل
            'data'    => null
        ], 422));
    }
}
