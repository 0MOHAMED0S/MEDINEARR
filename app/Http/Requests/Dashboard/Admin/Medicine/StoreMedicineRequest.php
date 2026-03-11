<?php

namespace App\Http\Requests\Dashboard\Admin\Medicine;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicineRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:medicines,name', // يمنع تكرار الاسم
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id', // يجب أن يكون التصنيف موجوداً
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'nullable|in:0,1',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'اسم الدواء',
            'description' => 'وصف الدواء',
            'category_id' => 'القسم / التصنيف',
            'image' => 'صورة الدواء',
            'status' => 'حالة الدواء',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'حقل :attribute مطلوب.',
            'name.unique' => 'هذا الدواء مسجل مسبقاً في النظام.',
            'category_id.required' => 'يرجى اختيار :attribute.',
            'category_id.exists' => 'التصنيف المختار غير صالح.',
            'description.required' => 'يرجى كتابة :attribute.',
            'image.required' => 'يرجى رفع :attribute.',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة صالحة.',
            'image.max' => 'حجم :attribute يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
