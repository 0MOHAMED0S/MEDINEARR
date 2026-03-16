<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
}
