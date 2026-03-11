<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\PharmacyApplication;
use Illuminate\Http\Request;

class AdminPharmacyController extends Controller
{
    public function index()
    {
        // 1. جلب جميع طلبات الصيدليات (الأحدث أولاً)
        $pharmacies = PharmacyApplication::latest()->get();

        // 2. حساب الإحصائيات لعرضها في البطاقات العلوية
        $stats = [
            'total'        => $pharmacies->count(),
            'approved'     => $pharmacies->where('status', 'approved')->count(),
            'under_review' => $pharmacies->where('status', 'under_review')->count(),
            'rejected'     => $pharmacies->where('status', 'rejected')->count(),
        ];

        // 3. إرسال البيانات إلى الـ View
        return view('dashboard.pharmacies.index', compact('pharmacies', 'stats'));
    }
}
