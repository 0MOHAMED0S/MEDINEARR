<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\Medicine\StoreMedicineRequest;
use App\Http\Requests\Dashboard\Admin\Medicine\UpdateMedicineRequest;
use App\Models\Medicine;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class AdminMedicineController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Medicine::count(),
            'active' => Medicine::where('status', 1)->count(),
            'inactive' => Medicine::where('status', 0)->count(),
        ];
        $medicines = Medicine::with('category')->orderBy('id', 'desc')->get();
        $categories = Category::where('status', 1)->get();
        return view('dashboard.medicines.index', compact('medicines', 'stats', 'categories'));
    }

    public function store(StoreMedicineRequest $request)
    {
        $data = $request->except('image');
        $data['status'] = $request->has('status') ? 1 : 0;

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
        $data['status'] = $request->has('status') ? 1 : 0;

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
