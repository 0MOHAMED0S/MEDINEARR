<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\Pharmacy\PharmacyStoreRequest;
use App\Models\PharmacyApplication;
use Illuminate\Support\Facades\Auth;

class PharmacyApplicationController extends Controller
{
    public function index()
    {
        $activeApplication = null;
        $rejectedApplications = collect(); // مجموعة فارغة
        if (Auth::check()) {
            $userId = Auth::id();
            $activeApplication = PharmacyApplication::where('user_id', $userId)
                ->whereIn('status', ['under_review', 'approved'])
                ->first();
            $rejectedApplications = PharmacyApplication::where('user_id', $userId)
                ->where('status', 'rejected')
                ->latest()
                ->get();
        }
        return view('main.pharmacy', compact('activeApplication', 'rejectedApplications'));
    }
    public function store(PharmacyStoreRequest $request)
    {
        $data = $request->validated();

        // Handle File Uploads
        if ($request->hasFile('license_document')) {
            $data['license_document'] = $request->file('license_document')->store('licenses', 'public');
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('pharmacies', 'public');
        }

        $data['user_id'] = auth()->id();
        $data['has_collaboration'] = $request->collab === 'yes';
        $data['status'] = 'under_review';

        PharmacyApplication::create($data);

        return back()->with('success', 'Application submitted successfully!');
    }
}
