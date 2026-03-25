<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\Medicine\StoreMedicineRequest;
use App\Http\Requests\Dashboard\Admin\Medicine\UpdateMedicineRequest;
use App\Models\Medicine;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with('category');

        // 1. الفلترة حسب القسم (Category)
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        // 2. الفلترة حسب الحالة (Status)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status === '1' ? 1 : 0);
        }

        // 3. البحث النصي (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $medicines = $query->orderBy('id', 'desc')->paginate(10)->appends($request->query());

        $stats = [
            'total'    => Medicine::count(),
            'active'   => Medicine::where('status', 1)->count(),
            'inactive' => Medicine::where('status', 0)->count(),
        ];

        $categories = Category::where('status', 1)->get();

        return view('dashboard.medicines.index', compact('medicines', 'stats', 'categories'));
    }

    public function store(StoreMedicineRequest $request)
    {
        $data = $request->except('image');

        // التقاط الـ Checkboxes وتحويلها إلى Boolean
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['is_price_fixed'] = $request->has('is_price_fixed') ? 1 : 0;

        // ✨ The Fix: Catching null and defaulting to 0 to prevent SQL Error 1048
        $data['official_price'] = $request->official_price ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('medicines', 'public');
        }

        Medicine::create($data);

        return redirect()->route('medicines.index')
            ->with('success', 'تم إضافة الدواء بنجاح.');
    }

    public function update(UpdateMedicineRequest $request, string $id)
    {
        $medicine = Medicine::findOrFail($id);
        $data = $request->except('image');

        // التقاط الـ Checkboxes
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['is_price_fixed'] = $request->has('is_price_fixed') ? 1 : 0;

        // ✨ The Fix: Catching null and defaulting to 0 to prevent SQL Error 1048
        $data['official_price'] = $request->official_price ?? 0;

        if ($request->hasFile('image')) {
            if ($medicine->image && Storage::disk('public')->exists($medicine->image)) {
                Storage::disk('public')->delete($medicine->image);
            }
            $data['image'] = $request->file('image')->store('medicines', 'public');
        }

        $medicine->update($data);

        return redirect()->route('medicines.index')
            ->with('success', 'تم تحديث الدواء بنجاح.');
    }

    public function destroy(string $id)
    {
        $medicine = Medicine::findOrFail($id);

        if ($medicine->image && Storage::disk('public')->exists($medicine->image)) {
            Storage::disk('public')->delete($medicine->image);
        }

        $medicine->delete();

        return redirect()->route('medicines.index')
            ->with('success', 'تم حذف الدواء بنجاح.');
    }

    public function toggleStatus(string $id)
    {
        $medicine = Medicine::findOrFail($id);

        $medicine->status = !$medicine->status;
        $medicine->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير حالة الدواء بنجاح',
            'status' => $medicine->status
        ]);
    }
}
