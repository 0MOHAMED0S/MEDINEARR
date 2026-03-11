<?php

namespace App\Http\Requests\Dashboard\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class PharmacyStoreRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'pharmacy_name'    => 'required|string|max:255',
            'owner_name'       => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'email'            => 'required|email|max:255',
            'city'             => 'required|string|max:255',
            'address'          => 'required|string|max:500',
            'lat'              => 'required|numeric',
            'lng'              => 'required|numeric',
            'working_hours'    => 'required|string|max:255',
            'license_number'   => 'required|string|unique:pharmacy_applications,license_number',
            'license_document' => 'required|file|mimes:pdf,png,jpg,jpeg|max:10240', // Max 10MB
            'image'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',     // Max 2MB
            'services'         => 'required|array|min:1',
            'collab'           => 'required|string|in:yes,no'
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            // Pharmacy Name
            'pharmacy_name.required' => 'اسم الصيدلية مطلوب.',
            'pharmacy_name.string'   => 'اسم الصيدلية يجب أن يكون نصاً.',
            'pharmacy_name.max'      => 'اسم الصيدلية يجب ألا يتجاوز 255 حرفاً.',

            // Owner Name
            'owner_name.required' => 'اسم المالك مطلوب.',
            'owner_name.string'   => 'اسم المالك يجب أن يكون نصاً.',
            'owner_name.max'      => 'اسم المالك يجب ألا يتجاوز 255 حرفاً.',

            // Phone
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.string'   => 'رقم الهاتف يجب أن يكون نصاً صالحاً.',
            'phone.max'      => 'رقم الهاتف أطول من اللازم.',

            // Email
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email'    => 'يرجى إدخال بريد إلكتروني صحيح.',
            'email.max'      => 'البريد الإلكتروني أطول من اللازم.',

            // City & Address
            'city.required'    => 'المحافظة / المدينة مطلوبة.',
            'address.required' => 'العنوان بالكامل مطلوب.',

            // Location (Map)
            'lat.required' => 'يرجى تحديد موقع الصيدلية على الخريطة.',
            'lat.numeric'  => 'إحداثيات الخريطة غير صالحة.',
            'lng.required' => 'يرجى تحديد موقع الصيدلية على الخريطة.',
            'lng.numeric'  => 'إحداثيات الخريطة غير صالحة.',

            // Working Hours
            'working_hours.required' => 'ساعات العمل مطلوبة.',

            // License Number
            'license_number.required' => 'رقم الترخيص مطلوب.',
            'license_number.unique'   => 'رقم الترخيص هذا مسجل لدينا بالفعل.',

            // License Document
            'license_document.required' => 'يرجى رفع مستند الترخيص الخاص بالصيدلية.',
            'license_document.file'     => 'يجب أن يكون المرفق ملفاً صالحاً.',
            'license_document.mimes'    => 'صيغ الملفات المدعومة للترخيص هي: PDF, PNG, JPG, JPEG فقط.',
            'license_document.max'      => 'حجم مستند الترخيص يجب ألا يتجاوز 10 ميجابايت.',

            // Image
            'image.image' => 'يجب أن يكون الملف المرفوع صورة.',
            'image.mimes' => 'صيغ الصور المدعومة هي: JPG, JPEG, PNG فقط.',
            'image.max'   => 'حجم الصورة الشخصية يجب ألا يتجاوز 2 ميجابايت.',

            // Services
            'services.required' => 'يرجى تحديد خدمة واحدة على الأقل من الخدمات المتاحة.',
            'services.array'    => 'نسق إرسال الخدمات غير صالح.',
            'services.min'      => 'يجب اختيار خدمة واحدة على الأقل.',

            // Collaboration
            'collab.required' => 'يرجى تحديد ما إذا كنت تتعاون مع أطباء أو عيادات.',
            'collab.in'       => 'القيمة المحددة للتعاون غير صالحة.',
        ];
    }
}
