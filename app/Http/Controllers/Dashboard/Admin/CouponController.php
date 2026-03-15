<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(12);

        $now = Carbon::now()->startOfDay();
        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)
                ->where(function ($q) use ($now) {
                    $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', $now);
                })
                ->where(function ($q) {
                    $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit');
                })->count(),
            'inactive' => Coupon::where('is_active', false)
                ->orWhere(function ($q) use ($now) {
                    $q->whereNotNull('expiry_date')->where('expiry_date', '<', $now);
                })
                ->orWhere(function ($q) {
                    $q->whereNotNull('usage_limit')->whereRaw('used_count >= usage_limit');
                })->count(),
        ];

        return view('dashboard.coupons.index', compact('coupons', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:100',
            'code'           => 'required|string|max:50|unique:coupons,code',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'usage_limit'    => 'nullable|integer|min:1',
            'expiry_date'    => 'nullable|date|after_or_equal:today',
        ]);

        $coupon = Coupon::create([
            'title'          => $request->title,
            'code'           => Str::upper($request->code),
            'discount_type'  => $request->discount_type,
            'discount_value' => $request->discount_value,
            'usage_limit'    => $request->usage_limit,
            'expiry_date'    => $request->expiry_date,
            'is_active'      => true,
        ]);

        Log::info("Admin ID: " . auth()->id() . " created coupon: {$coupon->code}");
        return back()->with('success', 'تم إنشاء الكوبون بنجاح.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'title'          => 'required|string|max:100',
            'code'           => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'usage_limit'    => 'nullable|integer|min:1',
            'expiry_date'    => 'nullable|date',
        ]);

        $coupon->update([
            'title'          => $request->title,
            'code'           => Str::upper($request->code),
            'discount_type'  => $request->discount_type,
            'discount_value' => $request->discount_value,
            'usage_limit'    => $request->usage_limit,
            'expiry_date'    => $request->expiry_date,
        ]);

        Log::info("Admin ID: " . auth()->id() . " updated coupon: {$coupon->code}");
        return back()->with('success', 'تم تعديل الكوبون بنجاح.');
    }

    public function destroy(Coupon $coupon)
    {
        $couponCode = $coupon->code;
        $coupon->delete();

        Log::info("Admin ID: " . auth()->id() . " deleted coupon: {$couponCode}");
        return back()->with('success', 'تم حذف الكوبون بشكل نهائي.');
    }

    public function toggleStatus(Coupon $coupon)
    {
        try {
            // حماية: يمنع تفعيل الكوبون المنتهي أو المكتمل
            if (!$coupon->is_active) {
                if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                    return response()->json(['success' => false, 'message' => 'مكتمل العدد، لا يمكن تفعيله.'], 400);
                }
                if ($coupon->expiry_date && Carbon::parse($coupon->expiry_date)->isPast()) {
                    return response()->json(['success' => false, 'message' => 'منتهي الصلاحية، لا يمكن تفعيله.'], 400);
                }
            }

            $coupon->update(['is_active' => !$coupon->is_active]);

            Log::info("Admin ID: " . auth()->id() . " toggled status for coupon: {$coupon->code} to " . ($coupon->is_active ? 'Active' : 'Inactive'));

            return response()->json([
                'success'   => true,
                'message'   => $coupon->is_active ? 'تم التفعيل بنجاح' : 'تم الإيقاف',
                'is_active' => $coupon->is_active
            ]);
        } catch (\Exception $e) {
            Log::error("Toggle Status Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ غير متوقع بالخادم.'], 500);
        }
    }
}
