<?php

namespace Database\Seeders;

use App\Models\PharmacyApplication;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class PharmacySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_EG');

        // 1. حساب تجريبي ثابت
        $mainUser = User::updateOrCreate(
            ['email' => 'pharmacy@medinear.com'],
            [
                'name'              => 'Mohamed Sayed',
                'password'          => Hash::make('password'),
                'role'              => 'pharmacy',
                'email_verified_at' => now(),
            ]
        );

        PharmacyApplication::updateOrCreate(
            ['user_id' => $mainUser->id],
            [
                'pharmacy_name'     => 'صيدلية ميدينير المركزية',
                'owner_name'        => 'Mohamed Sayed',
                'phone'             => '01012345678',
                'email'             => 'pharmacy@medinear.com',
                'city'              => 'بني سويف',
                'address'           => 'شارع كورنيش النيل، بجوار مبنى المحافظة',
                'lat'               => 29.0661,
                'lng'               => 31.0994,
                'working_hours'     => '24/7',
                'license_number'    => 'PH-12345',
                'license_document'  => 'licenses/sample.pdf',
                'services'          => ['24_hours', 'delivery'], // Laravel casts this to JSON automatically if set in model
                'status'            => 'under_review',
                'has_collaboration' => true,
            ]
        );

        // 2. توليد 10 طلبات عشوائية
        $statuses = ['approved', 'under_review', 'rejected'];
        $cities   = ['بني سويف', 'القاهرة', 'الجيزة', 'الإسكندرية', 'المنيا', 'الفيوم'];
        $servicesList = ['24_hours', 'delivery', 'consultation'];

        for ($i = 1; $i <= 10; $i++) {
            $email = "testpharmacy{$i}@medinear.com";

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'              => $faker->name,
                    'password'          => Hash::make('password'),
                    'role'              => 'pharmacy',
                    'email_verified_at' => now(),
                ]
            );

            $randomStatus = $faker->randomElement($statuses);
            $randomServices = $faker->randomElements($servicesList, $faker->numberBetween(1, 3));

            PharmacyApplication::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'pharmacy_name'     => 'صيدلية ' . $faker->lastName,
                    'owner_name'        => $user->name,
                    'phone'             => '01' . $faker->randomElement(['0', '1', '2', '5']) . $faker->numerify('########'),
                    'email'             => $user->email,
                    'city'              => $faker->randomElement($cities),
                    'address'           => $faker->address,
                    'lat'               => 29.0661 + ($faker->numberBetween(-5000, 5000) / 100000),
                    'lng'               => 31.0994 + ($faker->numberBetween(-5000, 5000) / 100000),
                    'working_hours'     => $faker->randomElement(['24/7', '08:00 AM - 12:00 AM', '10:00 AM - 10:00 PM']),
                    'license_number'    => 'PH-' . $faker->numerify('#####'),
                    'license_document'  => 'licenses/sample.pdf',
                    'services'          => $randomServices,
                    'status'            => $randomStatus,
                    'has_collaboration' => $faker->boolean,
                    'admin_notes'       => $randomStatus === 'rejected' ? $faker->randomElement(['صورة الترخيص غير واضحة', 'رقم الهاتف غير متاح', 'يرجى إرفاق بطاقة الهوية']) : null,
                ]
            );
        }
    }
}
