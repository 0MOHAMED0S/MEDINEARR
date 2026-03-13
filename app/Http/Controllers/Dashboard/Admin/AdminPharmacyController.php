<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminPharmacyController extends Controller
{
public function index()
{
    $pharmacies = Pharmacy::latest()->paginate(10);
    $stats = [
        'total'    => Pharmacy::count(),
        'active'   => Pharmacy::where('is_active', true)->count(),
        'inactive' => Pharmacy::where('is_active', false)->count(),
    ];

    return view('dashboard.pharmacies.index', compact('pharmacies', 'stats'));
}

    /**
     * AJAX Method to toggle pharmacy active status
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $pharmacy = Pharmacy::findOrFail($id);

            // Flip the boolean status
            $pharmacy->is_active = !$pharmacy->is_active;
            $pharmacy->save();

            return response()->json([
                'success'   => true,
                'message'   => $pharmacy->is_active ? 'تم تفعيل الصيدلية بنجاح.' : 'تم إيقاف الصيدلية بنجاح.',
                'is_active' => $pharmacy->is_active
            ], 200);

        } catch (\Exception $e) {
            Log::error('Admin Pharmacy Toggle Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير الحالة.'
            ], 500);
        }
    }
}
