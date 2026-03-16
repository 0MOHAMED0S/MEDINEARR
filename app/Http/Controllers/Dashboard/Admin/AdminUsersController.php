<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUsersController extends Controller
{
public function index(Request $request)
    {
        $query = User::query();

        // 1. الفلترة حسب الصلاحية (Role)
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // 2. الفلترة حسب حالة الحساب (Status: active / blocked)
        if ($request->filled('status') && $request->status !== 'all') {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }

        // 3. البحث النصي
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->appends($request->query());

        $stats = [
            'total'   => User::count(),
            'active'  => User::where('is_active', true)->count(),
            'blocked' => User::where('is_active', false)->count(),
        ];

        return view('dashboard.users.index', compact('users', 'stats'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'     => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'role'      => 'required|in:admin,user,pharmacy',
            'password'  => 'nullable|min:8',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($user->role === 'admin' && $request->role !== 'admin' && $user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك إزالة صلاحيات الإدارة عن حسابك الشخصي.');
        }

        $data = $request->only(['name', 'email', 'phone', 'role', 'latitude', 'longitude']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return back()->with('success', 'تم تحديث بيانات الحساب بنجاح.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'إجراء مرفوض: لا يمكن حذف حسابات المدراء من النظام.');
        }

        $user->delete();
        return back()->with('success', 'تم حذف المستخدم نهائياً من النظام.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->role === 'admin') {
            session()->flash('error', 'إجراء مرفوض: لا يمكن حظر حسابات المدراء.');
            return response()->json([
                'success' => false,
                'message' => 'إجراء مرفوض: لا يمكن حظر حسابات المدراء.',
                'reload'  => true
            ], 403);
        }

        $user->update(['is_active' => !$user->is_active]);

        $message = $user->is_active ? 'تم تفعيل الحساب بنجاح.' : 'تم حظر الحساب بنجاح.';
        session()->flash('success', $message);

        return response()->json([
            'success'   => true,
            'message'   => $message,
            'is_active' => $user->is_active,
            'reload'    => true
        ]);
    }
}
