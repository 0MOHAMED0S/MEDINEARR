<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\PharmacyApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminPharmacyApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = PharmacyApplication::query();

        // 1. الفلترة حسب الحالة (مقبول / مرفوض / مراجعة)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // 2. البحث النصي
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pharmacy_name', 'like', "%{$search}%")
                    ->orWhere('owner_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // 3. خريطة المحافظات والعدادات
        $governoratesMap = [
            'cairo' => 'القاهرة',
            'giza' => 'الجيزة',
            'alexandria' => 'الإسكندرية',
            'qalyubia' => 'القليوبية',
            'sharqia' => 'الشرقية',
            'dakahlia' => 'الدقهلية',
            'gharbia' => 'الغربية',
            'menofia' => 'المنوفية',
            'kafr_el_sheikh' => 'كفر الشيخ',
            'beheira' => 'البحيرة',
            'damietta' => 'دمياط',
            'port_said' => 'بورسعيد',
            'ismailia' => 'الإسماعيلية',
            'suez' => 'السويس',
            'fayoum' => 'الفيوم',
            'beni_suef' => 'بني سويف',
            'minya' => 'المنيا',
            'assiut' => 'أسيوط',
            'sohag' => 'سوهاج',
            'qena' => 'قنا',
            'luxor' => 'الأقصر',
            'aswan' => 'أسوان',
            'red_sea' => 'البحر الأحمر',
            'new_valley' => 'الوادي الجديد',
            'matrouh' => 'مطروح',
            'north_sinai' => 'شمال سيناء',
            'south_sinai' => 'جنوب سيناء'
        ];

        // حساب الأعداد قبل تطبيق فلتر الموقع (ليظل العداد ظاهراً)
        $govCountsQuery = clone $query;
        $govCountsDb = $govCountsQuery->select('city', DB::raw('count(*) as count'))->groupBy('city')->pluck('count', 'city')->toArray();

        $govCounts = [];
        $totalFilteredCount = 0;
        foreach ($governoratesMap as $key => $arName) {
            $count = $govCountsDb[$arName] ?? 0;
            $govCounts[$key] = $count;
            $totalFilteredCount += $count;
        }

        // 4. الفلترة الجغرافية على الجدول
        if ($request->filled('location') && $request->location !== 'all') {
            $cityArabicName = $governoratesMap[$request->location] ?? null;
            if ($cityArabicName) {
                $query->where('city', $cityArabicName);
            }
        }

        // ======================= التعديل الجديد للخريطة =======================
        // جلب الإحداثيات لكل الطلبات المفلترة لرسم الخريطة (بدون Pagination)
        $mapData = clone $query;
        $allMapPharmacies = $mapData->select('id', 'pharmacy_name', 'phone', 'address', 'lat', 'lng', 'status', 'image')->get();
        // ======================================================================

        // جلب البيانات بالصفحات مع إعطاء الأولوية للطلبات التي "تحت المراجعة"
        $pharmacies = $query->orderByRaw("
            CASE
                WHEN status = 'under_review' THEN 1
                ELSE 2
            END
        ")->latest()->paginate(10)->appends($request->query());

        // الإحصائيات العامة من كل الجدول
        $stats = [
            'total'        => PharmacyApplication::count(),
            'approved'     => PharmacyApplication::where('status', 'approved')->count(),
            'under_review' => PharmacyApplication::where('status', 'under_review')->count(),
            'rejected'     => PharmacyApplication::where('status', 'rejected')->count(),
        ];

        return view('dashboard.pharmaciesApplications.index', compact(
            'pharmacies',
            'stats',
            'govCounts',
            'totalFilteredCount',
            'governoratesMap',
            'allMapPharmacies'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'          => 'required|in:approved,rejected',
            'admin_notes'     => 'required_if:status,rejected|nullable|string|max:1000',
            'is_big_pharmacy' => 'nullable' // إضافة التحقق من الحقل الجديد
        ]);

        try {
            DB::beginTransaction();
            $application = PharmacyApplication::findOrFail($id);

            $application->status = $request->status;
            $application->admin_notes = $request->status === 'rejected' ? $request->admin_notes : null;
            $application->save();

            if ($request->status === 'approved') {

                // 1. البحث عن المستخدم بواسطة البريد الإلكتروني
                $user = User::where('email', $application->email)->first();

                // 2. إذا لم يتم العثور عليه بالإيميل، نحاول إيجاده بواسطة الـ user_id
                if (!$user && $application->user_id) {
                    $user = User::find($application->user_id);
                }

                if (!$user) {
                    throw new \Exception("لا يمكن قبول الصيدلية لعدم وجود حساب مستخدم (User) مرتبط بهذا البريد الإلكتروني أو الطلب.");
                }

                // ✨ ترقية حساب المستخدم ليكون صيدلية ✨
                $user->update(['role' => 'pharmacy']);

                // 3. إنشاء سجل الصيدلية وربطه بالمستخدم
                Pharmacy::firstOrCreate(
                    ['email' => $application->email],
                    [
                        'user_id'                 => $user->id,
                        'pharmacy_application_id' => $application->id,
                        'pharmacy_name'           => $application->pharmacy_name,
                        'owner_name'              => $application->owner_name,
                        'phone'                   => $application->phone,
                        'city'                    => $application->city,
                        'address'                 => $application->address,
                        'working_hours'           => $application->working_hours,
                        'license_number'          => $application->license_number,
                        'image'                   => $application->image,
                        'license_document'        => $application->license_document,
                        'lat'                     => $application->lat ?? null,
                        'lng'                     => $application->lng ?? null,
                        'services'                => is_string($application->services) ? json_decode($application->services, true) : $application->services,
                        'has_collaboration'       => $application->has_collaboration == 1 || $application->collab === 'yes',
                        'is_active'               => true,
                        // حفظ قيمة هل هي صيدلية كبرى
                        'is_big_pharmacy'         => $request->has('is_big_pharmacy') ? true : false,
                    ]
                );
            }

            DB::commit();
            $message = $request->status === 'approved' ? 'تم قبول الصيدلية وتفعيلها بنجاح.' : 'تم رفض طلب الصيدلية وحفظ ملاحظات الإدارة.';
            return back()->with('success', $message);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin Pharmacy Status Update Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pharmacy_name'     => 'required|string|max:255',
            'owner_name'        => 'required|string|max:255',
            'phone'             => 'required|string|max:20',
            'email'             => 'required|email|max:255',
            'city'              => 'nullable|string|max:255',
            'address'           => 'nullable|string',
            'working_hours'     => 'nullable|string|max:255',
            'lat'               => 'nullable|numeric',
            'lng'               => 'nullable|numeric',
            'has_collaboration' => 'required|boolean',
            'services'          => 'nullable|array',
        ]);

        try {
            $application = \App\Models\PharmacyApplication::findOrFail($id);

            if ($application->status !== 'under_review') {
                return back()->withErrors(['error' => 'لا يمكن تعديل بيانات طلب تمت معالجته مسبقاً.']);
            }

            $application->update([
                'pharmacy_name'     => $request->pharmacy_name,
                'owner_name'        => $request->owner_name,
                'phone'             => $request->phone,
                'email'             => $request->email,
                'city'              => $request->city,
                'address'           => $request->address,
                'working_hours'     => $request->working_hours,
                'lat'               => $request->lat,
                'lng'               => $request->lng,
                'has_collaboration' => $request->has_collaboration,
                'services'          => $request->services ?? [],
            ]);

            return back()->with('success', 'تم تحديث جميع بيانات الصيدلية بنجاح.');
        } catch (\Throwable $e) {
            Log::error('Admin Pharmacy Edit Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء التحديث. يرجى المحاولة مجدداً.']);
        }
    }

    public function destroy($id)
    {
        try {
            $application = PharmacyApplication::findOrFail($id);
            if ($application->status !== 'under_review') {
                return back()->withErrors(['error' => 'لا يمكن حذف طلب تمت معالجته بالفعل.']);
            }

            $application->delete();
            return back()->with('success', 'تم حذف الطلب نهائياً بنجاح.');
        } catch (\Throwable $e) {
            Log::error('Admin Pharmacy Delete Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء محاولة حذف الطلب.']);
        }
    }
}
