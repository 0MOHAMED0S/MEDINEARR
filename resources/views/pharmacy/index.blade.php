@extends('pharmacy.layout.master')

@section('title', 'لوحة التحكم والتحليلات')

@section('content')
<style>
    .dashboard-container {
        padding: 1.5rem;
        font-family: 'Cairo', 'Tajawal', sans-serif; /* خطوط عربية احترافية */
    }

    /* رسالة الترحيب */
    .welcome-banner {
        background: linear-gradient(135deg, #00965e 0%, #00b370 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 150, 94, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .welcome-text h2 { margin: 0; font-size: 1.5rem; font-weight: bold; }
    .welcome-text p { margin: 0.5rem 0 0; opacity: 0.9; font-size: 0.95rem; }

    /* شبكة الإحصائيات */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        border: 1px solid #f0f2f5;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem; /* RTL */
        font-size: 1.5rem;
    }
    .icon-green { background: #e6f5ef; color: #00965e; }
    .icon-blue { background: #e6f0ff; color: #0066ff; }
    .icon-orange { background: #fff0e6; color: #ff8800; }
    .icon-purple { background: #f0e6ff; color: #8800ff; }

    .stat-details h4 {
        margin: 0;
        font-size: 0.9rem;
        color: #6b7280;
        font-weight: 600;
    }
    .stat-details h3 {
        margin: 0.25rem 0 0;
        font-size: 1.5rem;
        color: #111827;
        font-weight: bold;
    }

    /* جدول الطلبات الأخيرة */
    .recent-orders-section {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        border: 1px solid #f0f2f5;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .section-header h3 { margin: 0; font-size: 1.2rem; color: #111827; font-weight: bold; }
    .view-all-btn {
        color: #00965e;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
    }
    .view-all-btn:hover { text-decoration: underline; }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }
    .custom-table th {
        background: #f8fafc;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 1rem;
        text-align: right; /* RTL */
        border-bottom: 1px solid #e5e7eb;
    }
    .custom-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        font-size: 0.9rem;
        vertical-align: middle;
    }
    .custom-table tr:last-child td { border-bottom: none; }
    .custom-table tr:hover td { background: #fdfdfd; }

    /* الحالات (Badges) */
    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-accepted { background: #cce5ff; color: #004085; }
    .badge-delivered { background: #d4edda; color: #155724; }

    /* زر الإجراء */
    .action-btn {
        background: #f3f4f6;
        color: #374151;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: 0.2s;
        text-decoration: none;
    }
    .action-btn:hover { background: #e5e7eb; color: #00965e; }
</style>

<div class="dashboard-container">

    <div class="welcome-banner">
        <div class="welcome-text">
            <h2>مرحباً بك مجدداً يا دكتور! 👋</h2>
            <p>إليك ملخص سريع لأداء صيدليتك اليوم، دعنا نحقق المزيد من المبيعات.</p>
        </div>
        </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-orange">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div class="stat-details">
                <h4>طلبات قيد الانتظار</h4>
                <h3>12</h3> </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-green">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="stat-details">
                <h4>أرباح المحفظة</h4>
                <h3>4,520 ج.م</h3> </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <div class="stat-details">
                <h4>أدوية المخزن</h4>
                <h3>845</h3> </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-purple">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="stat-details">
                <h4>طلبات مكتملة (اليوم)</h4>
                <h3>28</h3> </div>
        </div>
    </div>

    <div class="recent-orders-section">
        <div class="section-header">
            <h3>أحدث الطلبات (تحتاج لاتخاذ إجراء)</h3>
            <a href="#" class="view-all-btn">عرض الكل &larr;</a>
        </div>

        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>طريقة الدفع</th>
                        <th>الإجمالي</th>
                        <th>الحالة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>#MED-58392</strong></td>
                        <td>أحمد محمد</td>
                        <td>عند الاستلام</td>
                        <td>125.50 ج.م</td>
                        <td><span class="badge badge-pending">قيد الانتظار</span></td>
                        <td><a href="#" class="action-btn">عرض التفاصيل</a></td>
                    </tr>
                    <tr>
                        <td><strong>#MED-58391</strong></td>
                        <td>سارة محمود</td>
                        <td>Paymob</td>
                        <td>340.00 ج.م</td>
                        <td><span class="badge badge-accepted">جاري التجهيز</span></td>
                        <td><a href="#" class="action-btn">تحديث الحالة</a></td>
                    </tr>
                    <tr>
                        <td><strong>#MED-58390</strong></td>
                        <td>كريم مصطفى</td>
                        <td>عند الاستلام</td>
                        <td>85.00 ج.م</td>
                        <td><span class="badge badge-delivered">تم التوصيل</span></td>
                        <td><a href="#" class="action-btn">عرض التفاصيل</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
