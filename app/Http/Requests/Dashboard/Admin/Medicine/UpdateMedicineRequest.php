<?php

namespace App\Http\Requests\Dashboard\Admin\Medicine;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        // التقاط الـ ID الخاص بالدواء من الرابط لاستثنائه من التحقق من التكرار
        $medicineId = $this->route('medicine');

        return [
            'name' => 'required|string|max:255|unique:medicines,name,' . $medicineId,
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            'description.required' => 'يرجى كتابة :attribute.',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة صالحة.',
            'image.max' => 'حجم :attribute يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
