<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Save\SavePharmaciesRequest;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class SavePharmaciesController extends Controller
{
    /**
     * حفظ / إزالة صيدلية من المفضلة
     */
public function togglePharmacy(SavePharmaciesRequest $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to manage your saved pharmacies.',
                    'data'    => null
                ], 401);
            }

            $pharmacyId = (int) $request->input('pharmacy_id');

            // ✨ Security/Logic Check: Ensure the pharmacy actually exists AND is active ✨
            $pharmacy = Pharmacy::find($pharmacyId);

            if (!$pharmacy || !$pharmacy->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This pharmacy is currently inactive or unavailable.',
                    'data'    => null
                ], 403); // 403 Forbidden or 404 Not Found depending on your API standards
            }

            // Proceed with the toggle action since the pharmacy is active
            $result = $user->savedPharmacies()->toggle($pharmacyId);

            $isSaved = count($result['attached']) > 0;

            $message = $isSaved
                ? 'Pharmacy has been successfully added to your saved list.'
                : 'Pharmacy has been successfully removed from your saved list.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => [
                    'pharmacy_id' => $pharmacyId,
                    'is_saved'    => $isSaved
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Toggle Saved Pharmacy Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'We encountered an issue while processing your request. Please try again.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
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
                    'message' => 'Please log in to view your saved pharmacies.',
                    'data'    => null
                ], 401);
            }

            $perPage = (int) $request->input('per_page', 10);

            $query = $user->savedPharmacies()->where('pharmacies.is_active', true);

            if ($user->latitude && $user->longitude) {
                $lat = $user->latitude;
                $lng = $user->longitude;
                $radius = 6371;

                $haversineRaw = "( $radius * acos( cos(radians(?)) * cos(radians(pharmacies.lat)) * cos(radians(pharmacies.lng) - radians(?)) + sin(radians(?)) * sin(radians(pharmacies.lat)) ) )";
                $bindings = [$lat, $lng, $lat];

                $query->selectRaw("pharmacies.*, $haversineRaw AS distance", $bindings)
                    ->orderBy('distance', 'asc');
            } else {
                $query->select('pharmacies.*')->orderBy('saved_pharmacies.created_at', 'desc');
            }

            $pharmacies = $query->paginate($perPage)->withQueryString();

            // ✨ التعديل الأمني: Explicit Mapping ✨
            $pharmacies->getCollection()->transform(function ($pharmacy) {

                // حساب المسافة
                $distanceValue = null;
                $distanceText = null;

                if (isset($pharmacy->distance)) {
                    $distanceValue = round($pharmacy->distance, 2);
                    $distanceText = $pharmacy->distance < 1
                        ? round($pharmacy->distance * 1000) . ' m'
                        : $distanceValue . ' km';
                }

                // معالجة الصور
                $imageUrl = null;
                if (!empty($pharmacy->image)) {
                    $imageUrl = str_starts_with($pharmacy->image, 'http') ? $pharmacy->image : asset('storage/' . $pharmacy->image);
                }

                $coverUrl = null;
                if (!empty($pharmacy->cover)) {
                    $coverUrl = str_starts_with($pharmacy->cover, 'http') ? $pharmacy->cover : asset('storage/' . $pharmacy->cover);
                }

                // ✨ إرجاع الحقول العامة (الآمنة) فقط وإخفاء الباقي ✨
                return [
                    'pharmacy_id'       => $pharmacy->id, // بدلاً من id لسهولة الاستخدام في الموبايل
                    'pharmacy_name'     => $pharmacy->pharmacy_name,
                    'owner_name'        => $pharmacy->owner_name,
                    'city'              => $pharmacy->city,
                    'address'           => $pharmacy->address,
                    'phone'             => $pharmacy->phone,
                    'working_hours'     => $pharmacy->working_hours,
                    'image'             => $imageUrl,
                    'cover'             => $coverUrl,
                    'location'          => [
                        'lat' => $pharmacy->lat ? (float) $pharmacy->lat : null,
                        'lng' => $pharmacy->lng ? (float) $pharmacy->lng : null,
                    ],
                    'distance'          => $distanceValue,
                    'distance_text'     => $distanceText,
                    'services'          => is_string($pharmacy->services) ? json_decode($pharmacy->services) : $pharmacy->services,
                    'has_collaboration' => (bool) $pharmacy->has_collaboration,
                    'is_big_pharmacy'   => (bool) $pharmacy->is_big_pharmacy,
                    'is_saved'          => true, // حقل تأكيدي
                ];
            });

            $message = $pharmacies->isEmpty()
                ? 'You haven\'t saved any pharmacies to your favorites yet.'
                : 'Saved pharmacies retrieved successfully.';

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
