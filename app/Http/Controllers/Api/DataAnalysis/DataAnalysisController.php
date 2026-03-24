<?php

namespace App\Http\Controllers\Api\DataAnalysis;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Pharmacy;
use App\Models\Medicine;
use App\Models\Category;
use App\Models\PharmacyApplication;
use App\Models\PharmacyMedicine;

class DataAnalysisController extends Controller
{
    /**
     * 1. API: بيانات المستخدمين لـ Power BI
     *
     * @return JsonResponse
     */
    public function users(): JsonResponse
    {
        try {
            $users = User::where('role', 'user')
                ->select(
                    'id',
                    'name',
                    'email',
                    'provider_type',
                    'is_active',
                    'latitude',
                    'longitude',
                    'created_at'
                )
                ->get()
                ->map(function ($user) {
                    return [
                        'ID'         => $user->id,
                        'Name'       => $user->name,
                        'Email'      => $user->email ?? 'Not Provided',
                        'Provider'   => $user->provider_type ? ucfirst($user->provider_type) : 'Email/Password',
                        'Status'     => $user->is_active ? 'Active' : 'Inactive',
                        'Latitude'   => $user->latitude ? (float) $user->latitude : null,
                        'Longitude'  => $user->longitude ? (float) $user->longitude : null,

                        // توحيد التواريخ
                        'Created_At' => $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : null,
                        'Date'       => $user->created_at ? $user->created_at->format('Y-m-d') : null,
                        'Month_Year' => $user->created_at ? $user->created_at->format('Y-m') : null,
                    ];
                })->toArray();

            return response()->json($users, 200);
        } catch (\Exception $e) {
            Log::error('Power BI Users API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 2. API: بيانات الصيدليات والطلبات المدمجة لـ Power BI
     *
     * @return JsonResponse
     */
    public function pharmacies(): JsonResponse
    {
        try {
            // أ. جلب الصيدليات المعتمدة
            $approvedPharmacies = Pharmacy::all()->map(function ($pharmacy) {
                $servicesArray = is_string($pharmacy->services) ? json_decode($pharmacy->services, true) : $pharmacy->services;
                $servicesText  = (is_array($servicesArray) && count($servicesArray) > 0) ? implode(', ', $servicesArray) : 'No Services';

                return [
                    'ID'                => $pharmacy->id,
                    'Pharmacy_Name'     => $pharmacy->pharmacy_name,
                    'Owner_Name'        => $pharmacy->owner_name,
                    'Email'             => $pharmacy->email,
                    'Phone'             => $pharmacy->phone,
                    'City'              => $pharmacy->city ?? 'Unknown',
                    'Latitude'          => $pharmacy->lat ? (float) $pharmacy->lat : null,
                    'Longitude'         => $pharmacy->lng ? (float) $pharmacy->lng : null,
                    'Status'            => 'Approved',
                    'Is_Active'         => (bool) $pharmacy->is_active,
                    'Is_Big_Pharmacy'   => (bool) $pharmacy->is_big_pharmacy,
                    'Has_Collaboration' => (bool) $pharmacy->has_collaboration,
                    'Services'          => $servicesText,

                    // توحيد التواريخ
                    'Created_At'        => $pharmacy->created_at ? $pharmacy->created_at->format('Y-m-d H:i:s') : null,
                    'Date'              => $pharmacy->created_at ? $pharmacy->created_at->format('Y-m-d') : null,
                    'Month_Year'        => $pharmacy->created_at ? $pharmacy->created_at->format('Y-m') : null,
                ];
            })->toArray();

            // ب. جلب الطلبات (قيد المراجعة والمرفوضة)
            $applications = PharmacyApplication::whereIn('status', ['under_review', 'rejected'])
                ->get()
                ->map(function ($app) {
                    $servicesArray = is_string($app->services) ? json_decode($app->services, true) : $app->services;
                    $servicesText  = (is_array($servicesArray) && count($servicesArray) > 0) ? implode(', ', $servicesArray) : 'No Services';
                    $statusLabel   = $app->status === 'under_review' ? 'Under Review' : 'Rejected';

                    return [
                        'ID'                => $app->id,
                        'Pharmacy_Name'     => $app->pharmacy_name,
                        'Owner_Name'        => $app->owner_name,
                        'Email'             => $app->email,
                        'Phone'             => $app->phone,
                        'City'              => $app->city ?? 'Unknown',
                        'Latitude'          => $app->lat ? (float) $app->lat : null,
                        'Longitude'         => $app->lng ? (float) $app->lng : null,
                        'Status'            => $statusLabel,
                        'Is_Active'         => false,
                        'Is_Big_Pharmacy'   => false,
                        'Has_Collaboration' => (bool) $app->has_collaboration,
                        'Services'          => $servicesText,

                        // توحيد التواريخ
                        'Created_At'        => $app->created_at ? $app->created_at->format('Y-m-d H:i:s') : null,
                        'Date'              => $app->created_at ? $app->created_at->format('Y-m-d') : null,
                        'Month_Year'        => $app->created_at ? $app->created_at->format('Y-m') : null,
                    ];
                })->toArray();

            // ج. دمج المصفوفتين
            $combinedData = array_merge($approvedPharmacies, $applications);

            return response()->json($combinedData, 200);
        } catch (\Exception $e) {
            Log::error('Power BI Pharmacies API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 3. API: بيانات الفئات (الأقسام) لـ Power BI
     *
     * @return JsonResponse
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = Category::withCount([
                'medicines as Total_Medicines',
                'medicines as Active_Medicines' => function ($query) {
                    $query->where('status', 1);
                }
            ])
                ->get()
                ->map(function ($category) {
                    return [
                        'ID'               => $category->id,
                        'Category_Name'    => $category->name,

                        // ✅ إضافة حالة القسم (نشط / متوقف)
                        'Is_Active'        => (bool) $category->status,

                        'Total_Medicines'  => (int) $category->Total_Medicines,
                        'Active_Medicines' => (int) $category->Active_Medicines,
                        'Activity_Rate_%'  => $category->Total_Medicines > 0
                            ? round(($category->Active_Medicines / $category->Total_Medicines) * 100, 2)
                            : 0,

                        // توحيد التواريخ
                        'Created_At'       => $category->created_at ? $category->created_at->format('Y-m-d H:i:s') : null,
                        'Date'             => $category->created_at ? $category->created_at->format('Y-m-d') : null,
                        'Month_Year'       => $category->created_at ? $category->created_at->format('Y-m') : null,
                    ];
                })->toArray();

            return response()->json($categories, 200);
        } catch (\Exception $e) {
            Log::error('Power BI Categories API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 4. API: بيانات الأدوية لـ Power BI
     *
     * @return JsonResponse
     */
    public function medicines(): JsonResponse
    {
        try {
            $medicines = Medicine::with('category:id,name')
                ->get()
                ->map(function ($medicine) {
                    return [
                        'ID'            => $medicine->id,
                        'Name'          => $medicine->name,
                        'Category_ID'   => $medicine->category_id,
                        'Category_Name' => $medicine->category->name ?? 'Uncategorized',
                        'Is_Active'     => (bool) $medicine->status,
                        'Description'   => $medicine->description ?? 'No Description',

                        // توحيد التواريخ
                        'Created_At'    => $medicine->created_at ? $medicine->created_at->format('Y-m-d H:i:s') : null,
                        'Date'          => $medicine->created_at ? $medicine->created_at->format('Y-m-d') : null,
                        'Month_Year'    => $medicine->created_at ? $medicine->created_at->format('Y-m') : null,
                    ];
                })->toArray();

            return response()->json($medicines, 200);
        } catch (\Exception $e) {
            Log::error('Power BI Medicines API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 5. API: بيانات مخزون الصيدليات (Fact Table) لـ Power BI
     *
     * @return JsonResponse
     */
    public function pharmacyInventory(): JsonResponse
    {
        try {
            // جلب المخزون مع علاقة الدواء فقط لمعرفة الـ Category_ID
            $inventory = PharmacyMedicine::with('medicine:id,category_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'Inventory_ID'    => $item->id,
                        'Pharmacy_ID'     => $item->pharmacy_id,
                        'Category_ID'     => $item->medicine->category_id ?? null,
                        'Medicine_ID'     => $item->medicine_id,

                        'Price'           => (float) $item->price,
                        'Quantity'        => (int) $item->quantity,

                        // نرسل الحالة الأصلية النصية
                        'Status'          => $item->status,

                        // قيمة منطقية (Boolean) لتسهيل الفلترة في Power BI
                        'Is_Available'    => ($item->status === 'available' && $item->quantity > 0) ? true : false,

                        // توحيد التواريخ (مبنية على آخر تحديث للمخزون)
                        'Last_Update'     => $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : null,
                        'Date'            => $item->updated_at ? $item->updated_at->format('Y-m-d') : null,
                        'Month_Year'      => $item->updated_at ? $item->updated_at->format('Y-m') : null,
                    ];
                })->toArray();

            return response()->json($inventory, 200);
        } catch (\Exception $e) {
            Log::error('Power BI Pharmacy Inventory API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error', 'message' => $e->getMessage()], 500);
        }
    }
}
