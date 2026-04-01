<?php

namespace App\Http\Controllers\Dashboard\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class PharmacyProfileController extends Controller
{
    /**
     * عرض صفحة الملف الشخصي
     */
    public function index()
    {
        try {
            $user = auth()->user();
            $pharmacy = $user->pharmacy;

            if (!$pharmacy) {
                return redirect()->route('dashboard')->with('error', 'لم يتم العثور على بيانات الصيدلية الخاصة بك.');
            }

            return view('pharmacy.profile.index', compact('user', 'pharmacy'));

        } catch (Exception $e) {
            Log::error('Error loading pharmacy profile: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل الصفحة.');
        }
    }

    /**
     * تحديث البيانات غير الحساسة للصيدلية
     */
    public function update(Request $request)
    {
        // 1. التحقق من صحة البيانات (Validation)
        // نضعها خارج الـ Try-Catch حتى يقوم Laravel بإرجاع الأخطاء للـ View تلقائياً
        $validated = $request->validate([
            'phone'             => ['nullable', 'string', 'regex:/^\+[1-9]\d{7,14}$/'], // المعيار الدولي E.164
            'city'              => 'nullable|string|min:2|max:100',
            'address'           => 'nullable|string|min:5|max:500',
            'working_hours'     => 'nullable|string|max:100',
            'services'          => 'nullable|array',
            'services.*'        => 'string|in:24_hours,delivery,measurements', // التحقق من القيم المدخلة
            'has_collaboration' => 'nullable|boolean',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB
            'cover'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096', // 4MB
        ], [
            // رسائل الخطأ المخصصة باللغة العربية
            'phone.regex'   => 'يرجى إدخال رقم هاتف دولي صحيح يبدأ بكود الدولة (مثال: +201110562097).',
            'image.image'   => 'الملف المرفق للشعار يجب أن يكون صورة.',
            'image.mimes'   => 'شعار الصيدلية يجب أن يكون بصيغة: jpeg, png, jpg, webp.',
            'image.max'     => 'حجم صورة الشعار يجب ألا يتجاوز 2 ميجابايت.',
            'cover.image'   => 'الملف المرفق للغلاف يجب أن يكون صورة.',
            'cover.mimes'   => 'صورة الغلاف يجب أن تكون بصيغة: jpeg, png, jpg, webp.',
            'cover.max'     => 'حجم صورة الغلاف يجب ألا يتجاوز 4 ميجابايت.',
            'services.*.in' => 'إحدى الخدمات المحددة غير صالحة.',
        ]);

        try {
            // بدء معاملة قاعدة البيانات (Database Transaction)
            DB::beginTransaction();

            $pharmacy = auth()->user()->pharmacy;

            // حماية إضافية: التأكد من وجود الصيدلية
            if (!$pharmacy) {
                throw new Exception('لا يوجد سجل صيدلية مرتبط بهذا الحساب.');
            }

            // 2. تجهيز البيانات للتحديث
            $dataToUpdate = [
                'phone'             => $validated['phone'] ?? $pharmacy->phone,
                'city'              => $validated['city'] ?? $pharmacy->city,
                'address'           => $validated['address'] ?? $pharmacy->address,
                'working_hours'     => $validated['working_hours'] ?? $pharmacy->working_hours,
                'services'          => $validated['services'] ?? [],
                'has_collaboration' => $request->has('has_collaboration'),
            ];

            // 3. معالجة صورة الشعار (Avatar)
            if ($request->hasFile('image')) {
                // مسح الصورة القديمة من السيرفر إذا كانت موجودة
                if ($pharmacy->image && Storage::disk('public')->exists($pharmacy->image)) {
                    Storage::disk('public')->delete($pharmacy->image);
                }
                // حفظ الصورة الجديدة
                $dataToUpdate['image'] = $request->file('image')->store('pharmacies/images', 'public');
            }

            // 4. معالجة صورة الغلاف (Cover)
            if ($request->hasFile('cover')) {
                // مسح الغلاف القديم من السيرفر إذا كان موجوداً
                if ($pharmacy->cover && Storage::disk('public')->exists($pharmacy->cover)) {
                    Storage::disk('public')->delete($pharmacy->cover);
                }
                // حفظ الغلاف الجديد
                $dataToUpdate['cover'] = $request->file('cover')->store('pharmacies/covers', 'public');
            }

            // 5. تنفيذ التحديث
            $pharmacy->update($dataToUpdate);

            // تأكيد العمليات في قاعدة البيانات
            DB::commit();

            return back()->with('success', 'تم تحديث بيانات الصيدلية بنجاح.');

        } catch (Exception $e) {
            // التراجع عن أي تغييرات في قاعدة البيانات في حال حدوث خطأ
            DB::rollBack();

            // تسجيل الخطأ في ملفات الـ Log للرجوع إليها لاحقاً
            Log::error('Pharmacy Profile Update Error (User ID: ' . auth()->id() . '): ' . $e->getMessage());

            // إعادة المستخدم مع رسالة خطأ واحتفاظه بالبيانات التي أدخلها (إلا الملفات)
            return back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.');
        }
    }
}
