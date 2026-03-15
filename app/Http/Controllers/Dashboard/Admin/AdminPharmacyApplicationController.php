<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\PharmacyApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminPharmacyApplicationController extends Controller
{
    public function index()
    {
        // Fetch all applications: "Under Review" first, then newest first
        $query = PharmacyApplication::orderByRaw("
            CASE
                WHEN status = 'under_review' THEN 1
                ELSE 2
            END
        ")->latest();
        // Stats calculated from all records
        $allPharmacies = PharmacyApplication::all();
        $stats = [
            'total'        => $allPharmacies->count(),
            'approved'     => $allPharmacies->where('status', 'approved')->count(),
            'under_review' => $allPharmacies->where('status', 'under_review')->count(),
            'rejected'     => $allPharmacies->where('status', 'rejected')->count(),
        ];
        // Use Paginate for the main variable
        $pharmacies = $query->paginate(10);
        return view('dashboard.pharmaciesApplications.index', compact('pharmacies', 'stats'));
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'      => 'required|in:approved,rejected',
            'admin_notes' => 'required_if:status,rejected|nullable|string|max:1000'
        ]);
        try {
            DB::beginTransaction();
            $application = PharmacyApplication::findOrFail($id);
            // استخدام التعيين المباشر لضمان الحفظ وتجنب مشكلة الـ fillable
            $application->status = $request->status;
            $application->admin_notes = $request->status === 'rejected' ? $request->admin_notes : null;
            $application->save(); // حفظ البيانات صراحةً
            if ($request->status === 'approved') {
                $user = User::where('email', $application->email)->first();
                $userId = $user ? $user->id : ($application->user_id ?? null);
                if (!$userId) {
                    throw new \Exception("لا يمكن قبول الصيدلية لعدم وجود حساب مستخدم (User) مرتبط بهذا البريد الإلكتروني.");
                }
                Pharmacy::firstOrCreate(
                    ['email' => $application->email],
                    [
                        'user_id'                 => $userId,
                        'pharmacy_application_id' => $application->id,
                        'pharmacy_name'           => $application->pharmacy_name,
                        'owner_name'              => $application->owner_name,
                        'phone'                   => $application->phone,
                        'city'                    => $application->city,
                        'address'                 => $application->address,
                        'working_hours'           => $application->working_hours,
                        'license_number'          => $application->license_number,
                        'image'                   => $application->image,
                        'license_document'        => $application->license_document,
                        'lat'                     => $application->lat ?? null,
                        'lng'                     => $application->lng ?? null,
                        'services'                => is_string($application->services) ? json_decode($application->services, true) : $application->services,
                        'has_collaboration'       => $application->has_collaboration == 1 || $application->collab === 'yes',
                        'is_active'               => true,
                    ]
                );
            }
            DB::commit();
            $message = $request->status === 'approved' ? 'تم قبول الصيدلية وتفعيلها بنجاح.' : 'تم رفض طلب الصيدلية وحفظ ملاحظات الإدارة.';
            return back()->with('success', $message);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin Pharmacy Status Update Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'pharmacy_name'     => 'required|string|max:255',
            'owner_name'        => 'required|string|max:255',
            'phone'             => 'required|string|max:20',
            'email'             => 'required|email|max:255',
            'city'              => 'nullable|string|max:255',
            'address'           => 'nullable|string',
            'working_hours'     => 'nullable|string|max:255',
            'lat'               => 'nullable|numeric',
            'lng'               => 'nullable|numeric',
            'has_collaboration' => 'required|boolean',
            'services'          => 'nullable|array', // Because it's an array of checkboxes
        ]);

        try {
            $application = \App\Models\PharmacyApplication::findOrFail($id);

            // Security: Prevent editing if already approved/rejected
            if ($application->status !== 'under_review') {
                return back()->withErrors(['error' => 'لا يمكن تعديل بيانات طلب تمت معالجته مسبقاً.']);
            }

            // Update all fields
            $application->update([
                'pharmacy_name'     => $request->pharmacy_name,
                'owner_name'        => $request->owner_name,
                'phone'             => $request->phone,
                'email'             => $request->email,
                'city'              => $request->city,
                'address'           => $request->address,
                'working_hours'     => $request->working_hours,
                'lat'               => $request->lat,
                'lng'               => $request->lng,
                'has_collaboration' => $request->has_collaboration,
                'services'          => $request->services ?? [], // Save as array (casts to JSON automatically)
            ]);

            return back()->with('success', 'تم تحديث جميع بيانات الصيدلية بنجاح.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Admin Pharmacy Edit Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء التحديث. يرجى المحاولة مجدداً.']);
        }
    }
    public function destroy($id)
    {
        try {
            $application = PharmacyApplication::findOrFail($id);
            if ($application->status !== 'under_review') {
                return back()->withErrors(['error' => 'لا يمكن حذف طلب تمت معالجته بالفعل.']);
            }

            $application->delete();
            return back()->with('success', 'تم حذف الطلب نهائياً بنجاح.');
        } catch (\Throwable $e) {
            Log::error('Admin Pharmacy Delete Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء محاولة حذف الطلب.']);
        }
    }
}
