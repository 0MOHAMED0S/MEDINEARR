<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminPharmacyController extends Controller
{
public function index(Request $request)
    {
        $query = Pharmacy::query();

        // 1. الفلترة حسب الحالة (نشط / موقوف)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === '1' ? 1 : 0);
        }

        // 2. الفلترة حسب الحجم (صيدلية كبرى)
        if ($request->filled('big') && $request->big !== 'all') {
            $query->where('is_big_pharmacy', $request->big === '1' ? 1 : 0);
        }

        // 3. البحث النصي
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pharmacy_name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // خريطة المحافظات لربط القيمة الإنجليزية بالاسم العربي في الداتا بيز
        $governoratesMap = [
            'cairo' => 'القاهرة', 'giza' => 'الجيزة', 'alexandria' => 'الإسكندرية',
            'qalyubia' => 'القليوبية', 'sharqia' => 'الشرقية', 'dakahlia' => 'الدقهلية',
            'gharbia' => 'الغربية', 'menofia' => 'المنوفية', 'kafr_el_sheikh' => 'كفر الشيخ',
            'beheira' => 'البحيرة', 'damietta' => 'دمياط', 'port_said' => 'بورسعيد',
            'ismailia' => 'الإسماعيلية', 'suez' => 'السويس', 'fayoum' => 'الفيوم',
            'beni_suef' => 'بني سويف', 'minya' => 'المنيا', 'assiut' => 'أسيوط',
            'sohag' => 'سوهاج', 'qena' => 'قنا', 'luxor' => 'الأقصر', 'aswan' => 'أسوان',
            'red_sea' => 'البحر الأحمر', 'new_valley' => 'الوادي الجديد', 'matrouh' => 'مطروح',
            'north_sinai' => 'شمال سيناء', 'south_sinai' => 'جنوب سيناء'
        ];

        // 4. حساب أعداد الصيدليات في كل محافظة (لتغذية العدادات في الشاشة)
        $govCountsQuery = clone $query;
        $govCountsDb = $govCountsQuery->select('city', DB::raw('count(*) as count'))->groupBy('city')->pluck('count', 'city')->toArray();

        $govCounts = [];
        $totalFilteredCount = 0;
        foreach($governoratesMap as $key => $arName) {
            $count = $govCountsDb[$arName] ?? 0;
            $govCounts[$key] = $count;
            $totalFilteredCount += $count;
        }

        // 5. الفلترة الجغرافية (تطبق فقط على قائمة الجدول)
        if ($request->filled('location') && $request->location !== 'all') {
            $cityArabicName = $governoratesMap[$request->location] ?? null;
            if ($cityArabicName) {
                $query->where('city', $cityArabicName);
            }
        }

        // ======================= التعديل الجديد =======================
        // جلب جميع بيانات الصيدليات المطلوبة للخريطة فقط (بدون Pagination)
        // هذا يضمن عرض الدبابيس على الخريطة حتى لو كانت الصيدلية في الصفحة 2 أو 3
        $mapData = clone $query;
        $allMapPharmacies = $mapData->select('id', 'pharmacy_name', 'phone', 'address', 'lat', 'lng', 'is_active', 'is_big_pharmacy', 'image')->get();
        // ==============================================================

        $pharmacies = $query->latest()->paginate(10)->appends($request->query());

        $stats = [
            'total'    => Pharmacy::count(),
            'active'   => Pharmacy::where('is_active', true)->count(),
            'inactive' => Pharmacy::where('is_active', false)->count(),
            'big'      => Pharmacy::where('is_big_pharmacy', true)->count(),
        ];

        return view('dashboard.pharmacies.index', compact('pharmacies', 'stats', 'govCounts', 'totalFilteredCount', 'governoratesMap', 'allMapPharmacies'));
    }

    /**
     * AJAX Method to toggle pharmacy active status
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $pharmacy = Pharmacy::findOrFail($id);

            // Flip the boolean status
            $pharmacy->is_active = !$pharmacy->is_active;
            $pharmacy->save();

            return response()->json([
                'success'   => true,
                'message'   => $pharmacy->is_active ? 'تم تفعيل الصيدلية بنجاح.' : 'تم إيقاف الصيدلية بنجاح.',
                'is_active' => $pharmacy->is_active
            ], 200);

        } catch (\Exception $e) {
            Log::error('Admin Pharmacy Toggle Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير الحالة.'
            ], 500);
        }
    }

    /**
     * AJAX Method to toggle pharmacy "Big Pharmacy" status
     */
    public function toggleBigPharmacy(Request $request, $id)
    {
        try {
            $pharmacy = Pharmacy::findOrFail($id);

            // Flip the big pharmacy status
            $pharmacy->is_big_pharmacy = !$pharmacy->is_big_pharmacy;
            $pharmacy->save();

            return response()->json([
                'success'         => true,
                'message'         => $pharmacy->is_big_pharmacy ? 'تم تعيين الصيدلية كصيدلية كبرى بنجاح.' : 'تم إزالة تصنيف الصيدلية الكبرى.',
                'is_big_pharmacy' => $pharmacy->is_big_pharmacy
            ], 200);

        } catch (\Exception $e) {
            Log::error('Admin Big Pharmacy Toggle Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير التصنيف.'
            ], 500);
        }
    }


    /**
     * Show the form for editing the specified pharmacy.
     */
    public function edit($id)
    {
        try {
            $pharmacy = Pharmacy::findOrFail($id);
            return view('dashboard.pharmacies.edit', compact('pharmacy')); // تأكد من إنشاء هذا الملف في الـ Views
        } catch (\Exception $e) {
            Log::error('Admin Pharmacy Edit Error: ' . $e->getMessage());
            return redirect()->route('admin.pharmacies.index')->with('error', 'الصيدلية غير موجودة أو حدث خطأ.');
        }
    }

    /**
     * Update the specified pharmacy in storage.
     */
public function update(Request $request, $id)
    {
        // 1. Validation using 'sometimes' for flexible partial updates
        $validated = $request->validate([
            'pharmacy_name'  => 'sometimes|required|string|max:255',
            'owner_name'     => 'sometimes|required|string|max:255',
            'phone'          => 'sometimes|required|string|max:20',
            'email'          => 'sometimes|required|email|max:255|unique:pharmacies,email,' . $id,
            'city'           => 'sometimes|nullable|string|max:100',
            'address'        => 'sometimes|nullable|string|max:500',
            'working_hours'  => 'sometimes|nullable|string|max:100',
            'license_number' => 'sometimes|nullable|string|max:100',
            'image'          => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB Max
            'cover'          => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:4096', // 4MB Max
        ], [
            'image.image'  => 'يجب أن يكون شعار الصيدلية صورة صالحة.',
            'image.mimes'  => 'شعار الصيدلية يجب أن يكون بصيغة: jpeg, png, jpg, webp.',
            'cover.image'  => 'يجب أن تكون صورة الغلاف صورة صالحة.',
            'cover.mimes'  => 'صورة الغلاف يجب أن تكون بصيغة: jpeg, png, jpg, webp.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل لصيدلية أخرى بالفعل.'
        ]);

        try {
            DB::beginTransaction();

            $pharmacy = Pharmacy::findOrFail($id);

            // ✨ الطريقة الاحترافية: أخذ جميع البيانات الموثوقة واستبعاد الصور فقط ✨
            $dataToUpdate = \Illuminate\Support\Arr::except($validated, ['image', 'cover']);

            // 2. تحديث صورة الشعار (Logo/Image)
            if ($request->hasFile('image')) {
                // مسح الصورة القديمة إذا كانت موجودة
                if ($pharmacy->image && Storage::disk('public')->exists($pharmacy->image)) {
                    Storage::disk('public')->delete($pharmacy->image);
                }
                // رفع وحفظ المسار الجديد
                $dataToUpdate['image'] = $request->file('image')->store('pharmacies/images', 'public');
            }

            // 3. تحديث صورة الغلاف (Cover)
            if ($request->hasFile('cover')) {
                // مسح الغلاف القديم إذا كان موجوداً
                if ($pharmacy->cover && Storage::disk('public')->exists($pharmacy->cover)) {
                    Storage::disk('public')->delete($pharmacy->cover);
                }
                // رفع وحفظ المسار الجديد
                $dataToUpdate['cover'] = $request->file('cover')->store('pharmacies/covers', 'public');
            }

            // 4. تنفيذ التحديث في قاعدة البيانات (فقط إذا كان هناك بيانات للتحديث)
            if (!empty($dataToUpdate)) {
                $pharmacy->update($dataToUpdate);
            }

            DB::commit();
            return redirect()->back()->with('success', 'تم تحديث بيانات الصيدلية بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin Pharmacy Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء الحفظ. يرجى المحاولة مرة أخرى.');
        }
    }
}
