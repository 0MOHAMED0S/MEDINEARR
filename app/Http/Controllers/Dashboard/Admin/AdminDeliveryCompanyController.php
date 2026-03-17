<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryCompany;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
class AdminDeliveryCompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = DeliveryCompany::query();

        // الفلترة حسب الحالة
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status === '1' ? 1 : 0);
        }

        // البحث النصي
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $companies = $query->orderBy('id', 'desc')->paginate(10)->appends($request->query());

        $stats = [
            'total'    => DeliveryCompany::count(),
            'active'   => DeliveryCompany::where('status', 1)->count(),
            'inactive' => DeliveryCompany::where('status', 0)->count(),
        ];

        return view('dashboard.delivery_companies.index', compact('companies', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|unique:delivery_companies,email',
            'phone'  => 'required|string|max:20|unique:delivery_companies,phone',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'nullable|boolean'
        ]);

        $data = $request->only(['name', 'email', 'phone']);
        $data['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('delivery_companies', 'public');
        }

        DeliveryCompany::create($data);

        return back()->with('success', 'تمت إضافة شركة التوصيل بنجاح.');
    }

    public function update(Request $request, DeliveryCompany $deliveryCompany)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => ['nullable', 'email', Rule::unique('delivery_companies')->ignore($deliveryCompany->id)],
            'phone'  => ['required', 'string', 'max:20', Rule::unique('delivery_companies')->ignore($deliveryCompany->id)],
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'nullable|boolean'
        ]);

        $data = $request->only(['name', 'email', 'phone']);
        $data['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إن وجدت
            if ($deliveryCompany->image) {
                Storage::disk('public')->delete($deliveryCompany->image);
            }
            $data['image'] = $request->file('image')->store('delivery_companies', 'public');
        }

        $deliveryCompany->update($data);

        return back()->with('success', 'تم تحديث بيانات الشركة بنجاح.');
    }

    public function destroy(DeliveryCompany $deliveryCompany)
    {
        if ($deliveryCompany->image) {
            Storage::disk('public')->delete($deliveryCompany->image);
        }

        $deliveryCompany->delete();

        return back()->with('success', 'تم حذف شركة التوصيل نهائياً.');
    }

    public function toggleStatus(DeliveryCompany $deliveryCompany)
    {
        $deliveryCompany->update(['status' => !$deliveryCompany->status]);

        return response()->json([
            'success' => true,
            'message' => $deliveryCompany->status ? 'تم تفعيل الشركة بنجاح.' : 'تم إيقاف الشركة بنجاح.',
            'status'  => $deliveryCompany->status
        ]);
    }
}
