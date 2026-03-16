<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA'); // استخدام بيانات باللغة العربية

        // 2. إنشاء حساب صيدلية تجريبي (Pharmacy)
        User::firstOrCreate(
            ['email' => 'pharmacy@medinear.com'],
            [
                'name'              => 'صيدلية الأمل',
                'phone'             => '01111111111',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'role'              => 'pharmacy',
                'provider_type'     => null,
                'latitude'          => 31.2001, // الإسكندرية
                'longitude'         => 29.9187,
                'is_active'         => true,
            ]
        );

        // 3. إنشاء حساب مستخدم عادي تجريبي (User)
        User::firstOrCreate(
            ['email' => 'user@medinear.com'],
            [
                'name'              => 'مستخدم تجريبي',
                'phone'             => '01222222222',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'role'              => 'user',
                'provider_type'     => 'google',
                'provider_id'       => '1234567890',
                'latitude'          => 27.1810, // أسيوط
                'longitude'         => 31.1837,
                'is_active'         => true,
            ]
        );

        // 4. توليد 50 مستخدم عادي (User) ببيانات عشوائية لاختبار Power BI
        $providers = [null, 'google', 'facebook', 'apple'];

        for ($i = 0; $i < 50; $i++) {
            $provider = $faker->randomElement($providers);

            // توليد إحداثيات عشوائية تقريبية داخل مصر
            $randomLat = $faker->randomFloat(8, 24.0, 31.5);
            $randomLng = $faker->randomFloat(8, 25.0, 34.0);

            User::create([
                'name'              => $faker->name,
                'email'             => $faker->unique()->safeEmail,
                'phone'             => $faker->unique()->numerify('01#########'),
                'email_verified_at' => $faker->boolean(80) ? now() : null, // 80% مفعلين الإيميل
                'password'          => $provider ? null : Hash::make('password'),
                'role'              => 'user',
                'provider_type'     => $provider,
                'provider_id'       => $provider ? $faker->uuid : null,
                'latitude'          => $randomLat,
                'longitude'         => $randomLng,
                'is_active'         => $faker->boolean(90), // 90% من الحسابات نشطة
                'created_at'        => $faker->dateTimeBetween('-1 year', 'now'), // تواريخ تسجيل خلال السنة الماضية
                'updated_at'        => now(),
            ]);
        }
    }
}
