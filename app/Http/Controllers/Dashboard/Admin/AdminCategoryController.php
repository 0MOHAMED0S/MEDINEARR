<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Admin\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
public function index()
{
    $stats = [
        'total' => Category::count(),
        'active' => Category::where('status', 1)->count(),
        'inactive' => Category::where('status', 0)->count(),
    ];

    $categories = Category::withCount('medicines')->orderBy('id', 'desc')->get();

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
