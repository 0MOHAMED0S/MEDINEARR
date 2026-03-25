<?php

namespace App\Http\Controllers\Dashboard\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\PharmacyMedicine;
use Illuminate\Support\Facades\Log;
use Exception;

class PharmacyInventoryController extends Controller
{
    /**
     * عرض قائمة أدوية الصيدلية + قائمة الأدوية المتاحة للإضافة
     */
    public function index()
    {
        try {
            $pharmacy = Pharmacy::where('user_id', auth()->id())->first();

            if (!$pharmacy) {
                return redirect()->route('pharmacy.application.index')->with('error', 'لم يتم العثور على بيانات الصيدلية الخاصة بك.');
            }

            // جلب الأدوية من خلال العلاقة
            $myMedicines = $pharmacy->medicines()
                ->with('category')
                ->orderByPivot('created_at', 'desc')
                ->get();

            // استخراج أرقام الأدوية الموجودة لمنع تكرارها في قائمة الإضافة
            $existingMedicineIds = $myMedicines->pluck('id')->toArray();

            // جلب الأدوية العامة النشطة (لتظهر في القائمة المنسدلة للإضافة)
            $allMedicines = Medicine::where('status', 1)
                ->whereNotIn('id', $existingMedicineIds)
                ->get();

            return view('pharmacy.pharmacyMedicines.index', compact('myMedicines', 'allMedicines'));

        } catch (Exception $e) {
            Log::error('Error in PharmacyInventoryController@index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ غير متوقع أثناء جلب البيانات. يرجى المحاولة لاحقاً.');
        }
    }

    /**
     * إضافة دواء جديد لمخزون الصيدلية
     */
    public function store(Request $request)
    {
        // ⚠️ تم إزالة الـ required من الـ price لأن الجافاسكريبت قد يرسله فارغاً إذا كان readonly (مغلق)
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'price'       => 'nullable|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'status'      => 'required|in:available,out_of_stock,hidden',
            '_form_type'  => 'nullable|string'
        ]);

        try {
            $pharmacy = Pharmacy::where('user_id', auth()->id())->first();

            if (!$pharmacy) {
                return back()->with('error', 'لا تملك صلاحية أو صيدلية معتمدة لإضافة الأدوية.');
            }

            // التأكد الإضافي من أن الدواء غير موجود لتجنب تكراره
            $exists = PharmacyMedicine::where('pharmacy_id', $pharmacy->id)
                ->where('medicine_id', $request->medicine_id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'عذراً، هذا الدواء موجود بالفعل في مخزونك. يمكنك تعديل كميته بدلاً من إضافته مجدداً.');
            }

            // ----------------------------------------------------
            // ✨ الذكاء المنطقي التلقائي (Auto-Correction Logic) ✨
            // ----------------------------------------------------
            $status = $request->status;
            $quantity = $request->quantity;

            // لا نتدخل إذا كانت الحالة 'hidden' (لأن الصيدلي تعمد إخفاءه)
            if ($status !== 'hidden') {
                if ($quantity == 0) {
                    $status = 'out_of_stock'; // تحويل تلقائي لنفد المخزون إذا الكمية صفر
                } elseif ($quantity > 0) {
                    $status = 'available'; // تحويل تلقائي لمتوفر إذا الكمية أكبر من صفر
                }
            }

            // ----------------------------------------------------
            // ✨ الحماية الأمنية للتسعيرة الجبرية ✨
            // ----------------------------------------------------
            $medicine = Medicine::findOrFail($request->medicine_id);

            // إذا كان السعر ثابتاً نأخذ السعر الرسمي، وإلا نأخذ سعر الصيدلي (أو 0 إذا تركه فارغاً)
            $finalPrice = $medicine->is_price_fixed
                ? $medicine->official_price
                : ($request->price ?? 0);

            // الإضافة
            $inventoryItem = new PharmacyMedicine();
            $inventoryItem->pharmacy_id = $pharmacy->id;
            $inventoryItem->medicine_id = $request->medicine_id;
            $inventoryItem->price       = $finalPrice;
            $inventoryItem->quantity    = $quantity;
            $inventoryItem->status      = $status; // الحالة المصححة تلقائياً
            $inventoryItem->save();

            return back()->with('success', 'تم إضافة الدواء إلى مخزونك بنجاح.');
        } catch (Exception $e) {
            Log::error('Error in PharmacyInventoryController@store: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حفظ الدواء. يرجى التواصل مع الدعم الفني إذا استمرت المشكلة.');
        }
    }

    /**
     * تحديث بيانات دواء موجود (السعر، الكمية، الحالة)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'price'      => 'nullable|numeric|min:0', // nullable للسبب نفسه
            'quantity'   => 'required|integer|min:0',
            'status'     => 'required|in:available,out_of_stock,hidden',
            '_form_type' => 'nullable|string'
        ]);

        try {
            $pharmacy = Pharmacy::where('user_id', auth()->id())->first();

            if (!$pharmacy) {
                return back()->with('error', 'لا توجد بيانات صيدلية مرتبطة بحسابك.');
            }

            // جلب المخزون مع بيانات الدواء الأساسي لمعرفة حالة تسعيرته
            $inventoryItem = PharmacyMedicine::with('medicine')
                ->where('id', $id)
                ->where('pharmacy_id', $pharmacy->id)
                ->first();

            if (!$inventoryItem) {
                return back()->with('error', 'لم يتم العثور على هذا الدواء في مخزونك، أو لا تملك صلاحية تعديله.');
            }

            // ----------------------------------------------------
            // ✨ الذكاء المنطقي التلقائي عند التعديل ✨
            // ----------------------------------------------------
            $status = $request->status;
            $quantity = $request->quantity;

            if ($status !== 'hidden') {
                if ($quantity == 0) {
                    $status = 'out_of_stock';
                } elseif ($quantity > 0) {
                    $status = 'available';
                }
            }

            // ----------------------------------------------------
            // ✨ الحماية الأمنية للتسعيرة الجبرية ✨
            // ----------------------------------------------------
            $medicine = $inventoryItem->medicine;
            $finalPrice = $medicine->is_price_fixed
                ? $medicine->official_price
                : ($request->price ?? 0);

            // التحديث
            $inventoryItem->price    = $finalPrice;
            $inventoryItem->quantity = $quantity;
            $inventoryItem->status   = $status; // الحالة المصححة
            $inventoryItem->save();

            return back()->with('success', 'تم تحديث بيانات الدواء بنجاح.');
        } catch (Exception $e) {
            Log::error('Error in PharmacyInventoryController@update: ' . $e->getMessage());
            return back()->with('error', 'حدث خلل أثناء محاولة تحديث البيانات. يرجى المحاولة لاحقاً.');
        }
    }

    /**
     * إزالة الدواء من مخزون الصيدلية
     */
    public function destroy($id)
    {
        try {
            $pharmacy = Pharmacy::where('user_id', auth()->id())->first();

            if (!$pharmacy) {
                return back()->with('error', 'لا توجد بيانات صيدلية مرتبطة بحسابك.');
            }

            $inventoryItem = PharmacyMedicine::where('id', $id)
                ->where('pharmacy_id', $pharmacy->id)
                ->first();

            if (!$inventoryItem) {
                return back()->with('error', 'لا يمكن إتمام العملية، الدواء غير موجود في مخزونك.');
            }

            $inventoryItem->delete();

            return back()->with('success', 'تم إزالة الدواء من مخزونك بشكل نهائي.');
        } catch (Exception $e) {
            Log::error('Error in PharmacyInventoryController@destroy: ' . $e->getMessage());
            return back()->with('error', 'لم نتمكن من حذف الدواء بسبب خطأ في النظام. يرجى المحاولة لاحقاً.');
        }
    }
}
