<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Admin\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
public function index(\Illuminate\Http\Request $request)
    {
        $query = Category::withCount('medicines');

        // 1. الفلترة حسب الحالة (نشط / متوقف)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status === '1' ? 1 : 0);
        }

        // 2. البحث النصي (في اسم التصنيف أو الوصف)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // جلب البيانات مع الحفاظ على الفلاتر عند الانتقال بين الصفحات
        $categories = $query->orderBy('id', 'desc')->paginate(10)->appends($request->query());

        $stats = [
            'total'    => Category::count(),
            'active'   => Category::where('status', 1)->count(),
            'inactive' => Category::where('status', 0)->count(),
        ];

        return view('dashboard.categories.index', compact('categories', 'stats'));
    }


    public function store(StoreCategoryRequest $request)
    {
        $data = $request->except('image');
        $data['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'تم إضافة التصنيف بنجاح.');
    }
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->except('image');
        $data['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        $category->update($data);
        return redirect()->route('categories.index')
            ->with('success', 'تم تحديث التصنيف بنجاح.');
    }
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'تم حذف التصنيف بنجاح.');
    }
    public function toggleStatus(string $id)
    {
        $category = Category::findOrFail($id);

        $category->status = !$category->status;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير حالة التصنيف بنجاح',
            'status' => $category->status
        ]);
    }
}
