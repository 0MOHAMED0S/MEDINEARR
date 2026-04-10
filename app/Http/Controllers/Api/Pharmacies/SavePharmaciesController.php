<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Save\SavePharmaciesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SavePharmaciesController extends Controller
{


    public function togglePharmacy(SavePharmaciesRequest $request)
    {
        try {
            $user = auth()->user();

            // 1. التحقق من المصادقة (الأمان)
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated access.',
                    'data'    => null
                ], 401);
            }

            // 2. تنفيذ عملية الـ Toggle في قاعدة البيانات
            $pharmacyId = $request->input('pharmacy_id');
            $result = $user->savedPharmacies()->toggle($pharmacyId);

            // 3. تحديد حالة الصيدلية بعد العملية وتجهيز الرسالة
            $isSaved = count($result['attached']) > 0;
            $message = $isSaved ? 'Pharmacy saved successfully.' : 'Pharmacy removed from saved list.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => [
                    'pharmacy_id' => (int) $pharmacyId,
                    'is_saved'    => $isSaved
                ]
            ], 200);
        } catch (\Exception $e) {
            // 4. التقاط الأخطاء وتسجيلها
            Log::error('API Toggle Saved Pharmacy Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while toggling the pharmacy.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * جلب قائمة الصيدليات المحفوظة للمستخدم
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated access.',
                    'data'    => null
                ], 401);
            }

            $perPage = (int) $request->input('per_page', 10);

            // 1. جلب الصيدليات المحفوظة بشرط أن تكون مفعلة
            $query = $user->savedPharmacies()->where('pharmacies.is_active', true);

            // 2. حساب المسافة ذكياً إذا كان موقع المستخدم متاحاً
            if ($user->latitude && $user->longitude) {
                $lat = $user->latitude;
                $lng = $user->longitude;
                $radius = 6371; // Earth's radius in kilometers

                $haversineRaw = "( $radius * acos( cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)) ) )";
                $bindings = [$lat, $lng, $lat];

                $query->selectRaw("pharmacies.*, $haversineRaw AS distance", $bindings)
                    ->orderBy('distance', 'asc'); // الترتيب من الأقرب للأبعد
            } else {
                // إذا لم يكن هناك موقع، نرتبها حسب الأحدث إضافة للمفضلة
                $query->select('pharmacies.*')->orderBy('saved_pharmacies.created_at', 'desc');
            }

            // 3. تطبيق التقسيم (Pagination)
            $pharmacies = $query->paginate($perPage)->withQueryString();

            // 4. تهيئة المسافات للـ Mobile (Smart Distance Formatter)
            $pharmacies->getCollection()->transform(function ($pharmacy) {
                if (isset($pharmacy->distance)) {
                    if ($pharmacy->distance < 1) {
                        $pharmacy->distance_text = round($pharmacy->distance * 1000) . ' m';
                    } else {
                        $pharmacy->distance_text = round($pharmacy->distance, 2) . ' km';
                    }
                    $pharmacy->distance = round($pharmacy->distance, 2);
                } else {
                    $pharmacy->distance = null;
                    $pharmacy->distance_text = null;
                }

                // إزالة بيانات الجدول الوسيط (Pivot) لتنظيف استجابة الـ JSON
                unset($pharmacy->pivot);

                return $pharmacy;
            });

            // 5. تجهيز الرسالة النهائية
            $message = $pharmacies->isEmpty()
                ? 'You have no saved pharmacies yet.'
                : 'Saved pharmacies fetched successfully.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => $pharmacies
            ], 200);
        } catch (\Exception $e) {
            Log::error('API Fetch Saved Pharmacies Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while fetching your saved pharmacies.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }
}
