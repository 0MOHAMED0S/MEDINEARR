<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class AdminAdController extends Controller
{
    public function index()
    {
        $ads = Ad::latest()->paginate(12);
        // جلب الكوبونات النشطة لاستخدامها في Select الخاص بالربط
        $coupons = Coupon::where('is_active', true)->select('id', 'code', 'title')->get();

        $stats = [
            'total' => Ad::count(),
            'active' => Ad::where('is_active', true)->count(),
            'inactive' => Ad::where('is_active', false)->count(),
        ];

        return view('dashboard.ads.index', compact('ads', 'coupons', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:banner,cover',
            'title' => 'required|string|max:255',
            'link' => 'nullable|url',
            // Banner validation
            'description' => 'required_if:type,banner|nullable|string|max:255',
            'bg_color' => 'required_if:type,banner|nullable|string|max:20',
            'banner_image' => 'required_if:type,banner|nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'coupon_id' => 'nullable|exists:coupons,id',
            // Cover validation (Dimensions check can be added if needed e.g., dimensions:ratio=16/9)
            'cover_image' => 'required_if:type,cover|nullable|image|mimes:jpeg,png,jpg,webp|max:8120',
        ], [
            'banner_image.required_if' => 'الشعار مطلوب لإعلان البنر.',
            'cover_image.required_if' => 'صورة الغلاف مطلوبة لهذا النوع من الإعلانات.',
            'description.required_if' => 'الوصف مطلوب لإعلان البنر.'
        ]);

        $data = $request->except(['banner_image', 'cover_image']);
        $data['is_active'] = true;

        if ($request->type === 'banner' && $request->hasFile('banner_image')) {
            $data['image'] = $request->file('banner_image')->store('ads', 'public');
        } elseif ($request->type === 'cover' && $request->hasFile('cover_image')) {
            $data['image'] = $request->file('cover_image')->store('ads', 'public');
        }

        Ad::create($data);

        return back()->with('success', 'تم إضافة الإعلان بنجاح.');
    }

    public function update(Request $request, Ad $ad)
    {
        $request->validate([
            'type' => 'required|in:banner,cover',
            'title' => 'required|string|max:255',
            'link' => 'nullable|url',
            'description' => 'required_if:type,banner|nullable|string|max:255',
            'bg_color' => 'required_if:type,banner|nullable|string|max:20',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'coupon_id' => 'nullable|exists:coupons,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:9120',
        ]);

        $data = $request->except(['banner_image', 'cover_image']);

        // Only upload and replace if a new file is provided
        if ($request->type === 'banner' && $request->hasFile('banner_image')) {
            if ($ad->image) Storage::disk('public')->delete($ad->image);
            $data['image'] = $request->file('banner_image')->store('ads', 'public');
        } elseif ($request->type === 'cover' && $request->hasFile('cover_image')) {
            if ($ad->image) Storage::disk('public')->delete($ad->image);
            $data['image'] = $request->file('cover_image')->store('ads', 'public');
        }

        // Clean up fields based on type switch
        if ($request->type === 'cover') {
            $data['description'] = null;
            $data['bg_color'] = null;
            $data['coupon_id'] = null;
        }

        $ad->update($data);

        return back()->with('success', 'تم تعديل الإعلان بنجاح.');
    }

    public function destroy(Ad $ad)
    {
        if ($ad->image) Storage::disk('public')->delete($ad->image);
        $ad->delete();
        return back()->with('success', 'تم حذف الإعلان نهائياً.');
    }

    public function toggleStatus(Ad $ad)
    {
        $ad->update(['is_active' => !$ad->is_active]);
        return response()->json([
            'success' => true,
            'message' => $ad->is_active ? 'تم تفعيل الإعلان' : 'تم إيقاف الإعلان',
            'is_active' => $ad->is_active
        ]);
    }
}
