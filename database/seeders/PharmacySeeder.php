<?php

namespace Database\Seeders;

use App\Models\PharmacyApplication;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PharmacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Use Faker with Arabic localization for realistic Egyptian data
        $faker = Faker::create('ar_EG');

        // 1. Create your main fixed test account
        $mainUser = User::create([
            'name'              => 'Mohamed Sayed',
            'email'             => 'pharmacy@medinear.com',
            'password'          => bcrypt('password'),
            'role'              => 'pharmacy', // Crucial for your new security middleware!
            'email_verified_at' => now(),
        ]);

        PharmacyApplication::create([
            'user_id'           => $mainUser->id,
            'pharmacy_name'     => 'صيدلية ميدينير المركزية', // MediNear Central
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
            'services'          => ['24_hours', 'delivery'],
            'status'            => 'under_review',
            'has_collaboration' => true,
        ]);

        // 2. Generate 10 Random Pharmacy Applications
        $statuses = ['approved', 'under_review', 'rejected'];
        $cities   = ['بني سويف', 'القاهرة', 'الجيزة', 'الإسكندرية', 'المنيا', 'الفيوم'];
        $servicesList = ['24_hours', 'delivery', 'consultation'];

        for ($i = 1; $i <= 10; $i++) {

            // Create a User for each pharmacy
            $user = User::create([
                'name'              => $faker->name,
                'email'             => "testpharmacy{$i}@medinear.com", // Predictable emails for testing
                'password'          => bcrypt('password'),
                'role'              => 'pharmacy',
                'email_verified_at' => now(),
            ]);

            // Pick a random status
            $randomStatus = $faker->randomElement($statuses);

            // Generate 1 to 3 random services
            $randomServices = $faker->randomElements($servicesList, $faker->numberBetween(1, 3));

            PharmacyApplication::create([
                'user_id'           => $user->id,
                'pharmacy_name'     => 'صيدلية ' . $faker->lastName,
                'owner_name'        => $user->name,
                // Generates a valid Egyptian mobile format (e.g., 010, 011, 012, 015 + 8 digits)
                'phone'             => '01' . $faker->randomElement(['0', '1', '2', '5']) . $faker->numerify('########'),
                'email'             => $user->email,
                'city'              => $faker->randomElement($cities),
                'address'           => $faker->address,
                // Slightly randomize coordinates around Beni Suef / Cairo
                'lat'               => 29.0661 + ($faker->numberBetween(-5000, 5000) / 100000),
                'lng'               => 31.0994 + ($faker->numberBetween(-5000, 5000) / 100000),
                'working_hours'     => $faker->randomElement(['24/7', '08:00 AM - 12:00 AM', '10:00 AM - 10:00 PM']),
                'license_number'    => 'PH-' . $faker->numerify('#####'),
                'license_document'  => 'licenses/sample.pdf',
                'services'          => $randomServices,
                'status'            => $randomStatus,
                'has_collaboration' => $faker->boolean,
                // If rejected, add a fake admin note
                'admin_notes'       => $randomStatus === 'rejected' ? $faker->randomElement(['صورة الترخيص غير واضحة', 'رقم الهاتف غير متاح', 'يرجى إرفاق بطاقة الهوية']) : null,
            ]);
        }
    }
}
