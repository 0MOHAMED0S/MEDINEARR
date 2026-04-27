<?php

namespace App\Http\Controllers\Api\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{

//get all categories for user to show them in the home page

    /**
     * Display a listing of the resource.
     * try and catch
     */
    public function index(Request $request)
    {
        try {
            $perPage = min($request->input('per_page', 10), 50);

            $categories = Category::where('status', 1)
                ->select('id', 'name', 'image', 'description')
                ->latest()
                ->paginate($perPage)
                ->through(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'description' => $category->description,
                        'image' => $category->image
                            ? asset('storage/' . $category->image)
                            : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✨ جلب جميع الأدوية التابعة لقسم معين ✨
     */
    public function getCategoryMedicines(Request $request, $id)
    {
        try {
            // 1. التحقق من وجود القسم وأنه مفعل
            $category = Category::where('status', 1)->find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found or currently inactive.',
                    'data'    => null
                ], 404);
            }

            $perPage = min($request->input('per_page', 10), 50);

            // 2. جلب الأدوية المفعلة المرتبطة بهذا القسم
            $medicines = Medicine::where('category_id', $id)
                ->where('status', 1) // الأدوية المفعلة فقط
                ->latest()
                ->paginate($perPage)
                ->through(function ($medicine) {
                    return [
                        'id'             => $medicine->id,
                        'name'           => $medicine->name,
                        'description'    => $medicine->description,
                        'official_price' => $medicine->official_price ? (float) $medicine->official_price : null,
                        'image'          => $medicine->image ? (str_starts_with($medicine->image, 'http') ? $medicine->image : asset('storage/' . $medicine->image)) : null,
                    ];
                });

            // 3. تجهيز الرسالة
            $message = $medicines->isEmpty()
                ? 'No medicines found in this category yet.'
                : 'Category medicines retrieved successfully.';

            // 4. إرجاع الاستجابة (بها بيانات القسم بالإضافة للأدوية)
            return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => [
                    'category'  => [
                        'id'          => $category->id,
                        'name'        => $category->name,
                        'description' => $category->description,
                        'image'       => $category->image ? (str_starts_with($category->image, 'http') ? $category->image : asset('storage/' . $category->image)) : null,
                    ],
                    'medicines' => $medicines // سيتم إرجاعها كـ Pagination Object
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('API Get Category Medicines Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving medicines.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * جلب تفاصيل دواء معين مع قائمة الصيدليات المتاح بها (Product Details)
     * مع دعم الـ Pagination للصيدليات
     */
    public function getMedicineDetails(Request $request, $id)
    {
        try {
            $user = auth()->guard('sanctum')->user(); // استخدم 'api' لو كنت تستخدم JWT

            // 1. جلب بيانات الدواء الأساسية مع القسم التابع له
            $medicine =Medicine::with('category:id,name')
                ->where('status', 1)
                ->find($id);

            if (!$medicine) {
                return response()->json([
                    'success' => false,
                    'message' => 'Medicine not found or currently inactive.',
                    'data'    => null
                ], 404);
            }

            // 2. معالجة مسار صورة الدواء
            $medImage = null;
            if (!empty($medicine->image)) {
                $medImage = str_starts_with($medicine->image, 'http') ? $medicine->image : asset('storage/' . $medicine->image);
            }

            // 3. إعداد متغيرات الموقع الجغرافي وحجم الصفحة (Pagination)
            $lat = $user ? $user->latitude : null;
            $lng = $user ? $user->longitude : null;
            $radius = 6371;
            $perPage = (int) $request->input('per_page', 5); // ✨ تحديد عدد الصيدليات في الصفحة ✨

            $haversineRaw = "( $radius * acos( cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)) ) )";
            $bindings = [$lat, $lng, $lat];

            // 4. جلب الصيدليات المتاح بها هذا الدواء
            $pharmaciesQuery = \App\Models\Pharmacy::where('is_active', true)
                ->whereHas('medicines', function ($query) use ($id) {
                    $query->where('pharmacy_medicines.medicine_id', $id)
                          ->where('pharmacy_medicines.status', '!=', 'hidden');
                })
                ->with(['medicines' => function ($query) use ($id) {
                    $query->where('medicines.id', $id);
                }]);

            // ترتيب الصيدليات حسب الأقرب إذا كان موقع المستخدم متاحاً
            if ($lat && $lng) {
                $pharmaciesQuery->whereNotNull('lat')
                                ->whereNotNull('lng')
                                ->selectRaw("pharmacies.*, $haversineRaw AS distance", $bindings)
                                ->orderBy('distance', 'asc');
            } else {
                $pharmaciesQuery->select('pharmacies.*');
            }

            // ✨ 5. تطبيق الـ Pagination بدلاً من get() ✨
            $availablePharmacies = $pharmaciesQuery->paginate($perPage)->withQueryString();

            // ✨ 6. تنظيف بيانات الصيدليات داخل الـ Pagination Object ✨
            $availablePharmacies->getCollection()->transform(function ($pharmacy) {
                $pivotData = $pharmacy->medicines->first()?->pivot;

                // تنسيق المسافة
                $distanceValue = null;
                $distanceText = null;
                if (isset($pharmacy->distance)) {
                    $distanceValue = round($pharmacy->distance, 2);
                    $distanceText = $pharmacy->distance < 1
                        ? round($pharmacy->distance * 1000) . ' m'
                        : $distanceValue . ' km';
                }

                // معالجة صورة الصيدلية
                $pharmImage = null;
                if (!empty($pharmacy->image)) {
                    $pharmImage = str_starts_with($pharmacy->image, 'http') ? $pharmacy->image : asset('storage/' . $pharmacy->image);
                }

                return [
                    'pharmacy_id'   => $pharmacy->id,
                    'pharmacy_name' => $pharmacy->pharmacy_name,
                    'address'       => $pharmacy->address,
                    'working_hours' => $pharmacy->working_hours,
                    'image'         => $pharmImage,
                    'location'      => [
                        'lat' => $pharmacy->lat ? (float) $pharmacy->lat : null,
                        'lng' => $pharmacy->lng ? (float) $pharmacy->lng : null,
                    ],
                    'distance'      => $distanceValue,
                    'distance_text' => $distanceText,

                    // بيانات المخزون والسعر الخاصة بهذه الصيدلية
                    'stock_info'    => [
                        'price'    => $pivotData ? (float) $pivotData->price : null,
                        'quantity' => $pivotData ? (int) $pivotData->quantity : 0,
                        'status'   => $pivotData ? $pivotData->status : 'not_listed',
                        'in_stock' => $pivotData && ((int) $pivotData->quantity) > 0,
                    ]
                ];
            });

            // 7. التحقق مما إذا كان المستخدم قد حفظ هذا الدواء مسبقاً
            $isSaved = false;
            if ($user) {
                $isSaved = \App\Models\SavedMedicine::where('user_id', $user->id)
                    ->where('medicine_id', $id)
                    ->exists();
            }

            // 8. إرجاع الاستجابة النهائية المهيكلة
            return response()->json([
                'success' => true,
                'message' => 'Medicine details retrieved successfully.',
                'data'    => [
                    'medicine' => [
                        'id'             => $medicine->id,
                        'name'           => $medicine->name,
                        'description'    => $medicine->description,
                        'official_price' => $medicine->official_price ? (float) $medicine->official_price : null,
                        'image'          => $medImage,
                        'category'       => $medicine->category ? [
                            'id'   => $medicine->category->id,
                            'name' => $medicine->category->name
                        ] : null,
                        'is_saved'       => $isSaved,
                    ],
                    // سيتم إرجاع كائن الـ Pagination كاملاً هنا (يحتوي على current_page, data, total وغيرها)
                    'available_pharmacies' => $availablePharmacies
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Get Medicine Details Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving medicine details.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }
}
