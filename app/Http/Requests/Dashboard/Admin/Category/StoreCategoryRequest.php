<?php

namespace App\Http\Requests\Dashboard\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // تم السماح بتنفيذ الريكويست
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'nullable|in:0,1',
        ];
    }

    /**
     * تخصيص أسماء الحقول لتظهر باللغة العربية في رسائل الخطأ
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم التصنيف',
            'description' => 'وصف التصنيف',
            'image' => 'صورة التصنيف',
            'status' => 'حالة التصنيف',
        ];
    }

    /**
     * تخصيص رسائل الخطأ (Validation Messages) باللغة العربية
     */
    public function messages(): array
    {
        return [
            // رسائل حقل الاسم
            'name.required' => 'حقل :attribute مطلوب ولا يمكن تركه فارغاً.',
            'name.string' => 'يجب أن يكون :attribute نصاً.',
            'name.max' => 'يجب ألا يتجاوز طول :attribute 255 حرفاً.',

            // رسائل حقل الوصف
            'description.required' => 'حقل :attribute مطلوب، يرجى كتابة وصف قصير.',
            'description.string' => 'يجب أن يكون :attribute نصاً.',

            // رسائل حقل الصورة
            'image.required' => 'يرجى رفع :attribute، فهو حقل إجباري.',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة صالحة.',
            'image.mimes' => 'يجب أن تكون :attribute بأحد الصيغ التالية: jpeg, png, jpg, gif, svg.',
            'image.max' => 'حجم :attribute يجب ألا يتجاوز 2 ميجابايت (2048 كيلوبايت).',

            // رسائل حقل الحالة
            'status.in' => 'القيمة المحددة في :attribute غير صالحة، يجب أن تكون نشطة أو متوقفة فقط.',
        ];
    }
}
