<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            ['title' => 'خصم الشتاء المميز', 'code' => 'WINTER50', 'discount_type' => 'percentage', 'discount_value' => 50, 'usage_limit' => 100, 'used_count' => 10, 'expiry_date' => Carbon::now()->addDays(30), 'is_active' => true],
            ['title' => 'ترحيب بالعملاء الجدد', 'code' => 'WELCOME200', 'discount_type' => 'fixed', 'discount_value' => 200, 'usage_limit' => null, 'used_count' => 450, 'expiry_date' => Carbon::now()->addMonths(2), 'is_active' => true],
            ['title' => 'عروض الجمعة البيضاء', 'code' => 'FRIDAY100', 'discount_type' => 'percentage', 'discount_value' => 100, 'usage_limit' => 10, 'used_count' => 10, 'expiry_date' => Carbon::now()->addDays(5), 'is_active' => true], // مكتمل
            ['title' => 'خصم عيد الأم', 'code' => 'MOM50', 'discount_type' => 'fixed', 'discount_value' => 50, 'usage_limit' => 50, 'used_count' => 5, 'expiry_date' => Carbon::now()->subDays(10), 'is_active' => false], // منتهي
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
