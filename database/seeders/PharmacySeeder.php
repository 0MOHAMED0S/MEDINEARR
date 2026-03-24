<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pharmacy;
use App\Models\PharmacyApplication;
use App\Models\Medicine;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PharmacySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_EG');

        // ✨ جلب الأدوية مع أسعارها الرسمية وحالة السعر (ثابت أم مرن)
        $allMedicines = Medicine::select('id', 'official_price', 'is_price_fixed')->get();

        $egyptCities = [
            'القاهرة'       => ['lat' => 30.0444, 'lng' => 31.2357],
            'الجيزة'        => ['lat' => 30.0131, 'lng' => 31.2089],
            'الإسكندرية'    => ['lat' => 31.2001, 'lng' => 29.9187],
            'القليوبية'     => ['lat' => 30.4069, 'lng' => 31.1862],
            'الشرقية'       => ['lat' => 30.5877, 'lng' => 31.5020],
            'الدقهلية'      => ['lat' => 31.0379, 'lng' => 31.3815],
            'الغربية'       => ['lat' => 30.8754, 'lng' => 31.0335],
            'المنوفية'      => ['lat' => 30.5972, 'lng' => 30.9876],
            'كفر الشيخ'     => ['lat' => 31.1107, 'lng' => 30.9388],
            'البحيرة'       => ['lat' => 31.0409, 'lng' => 30.4700],
            'دمياط'         => ['lat' => 31.4165, 'lng' => 31.8133],
            'بورسعيد'       => ['lat' => 31.2565, 'lng' => 32.2841],
            'الإسماعيلية'   => ['lat' => 30.5965, 'lng' => 32.2715],
            'السويس'        => ['lat' => 29.9668, 'lng' => 32.5498],
            'الفيوم'        => ['lat' => 29.3084, 'lng' => 30.8428],
            'بني سويف'      => ['lat' => 29.0661, 'lng' => 31.0994],
            'المنيا'        => ['lat' => 28.0871, 'lng' => 30.7618],
            'أسيوط'         => ['lat' => 27.1810, 'lng' => 31.1837],
            'سوهاج'         => ['lat' => 26.5591, 'lng' => 31.6957],
            'قنا'           => ['lat' => 26.1551, 'lng' => 32.7160],
            'الأقصر'        => ['lat' => 25.6872, 'lng' => 32.6396],
            'أسوان'         => ['lat' => 24.0889, 'lng' => 32.8998],
            'البحر الأحمر'  => ['lat' => 27.2579, 'lng' => 33.8116],
            'الوادي الجديد' => ['lat' => 25.4514, 'lng' => 30.5463],
            'مطروح'         => ['lat' => 31.3525, 'lng' => 27.2373],
            'شمال سيناء'    => ['lat' => 31.1316, 'lng' => 33.7984],
            'جنوب سيناء'    => ['lat' => 28.2364, 'lng' => 33.6254],
        ];

        $servicesList = ['24_hours', 'delivery', 'consultation'];
        $providers = [null, 'google', 'facebook'];

        $applicationsQueue = [];
        $cityNames = array_keys($egyptCities);

        foreach ($cityNames as $city) {
            $applicationsQueue[] = ['status' => 'approved', 'city' => $city];
        }
        for ($i = 0; $i < 13; $i++) {
            $applicationsQueue[] = ['status' => 'approved', 'city' => $faker->randomElement($cityNames)];
        }
        for ($i = 0; $i < 25; $i++) {
            $applicationsQueue[] = ['status' => 'under_review', 'city' => $faker->randomElement($cityNames)];
        }
        for ($i = 0; $i < 15; $i++) {
            $applicationsQueue[] = ['status' => 'rejected', 'city' => $faker->randomElement($cityNames)];
        }
        shuffle($applicationsQueue);

        $mainUser = User::updateOrCreate(
            ['email' => 'pharmacy@medinear.com'],
            [
                'name'              => 'Mohamed Sayed',
                'password'          => Hash::make('password'),
                'role'              => 'pharmacy',
                'email_verified_at' => now(),
            ]
        );

        $mainApp = PharmacyApplication::updateOrCreate(
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
                'services'          => ['24_hours', 'delivery'],
                'status'            => 'approved',
                'has_collaboration' => true,
            ]
        );

        $mainPharmacy = Pharmacy::firstOrCreate(
            ['email' => 'pharmacy@medinear.com'],
            [
                'user_id'                 => $mainUser->id,
                'pharmacy_application_id' => $mainApp->id,
                'pharmacy_name'           => $mainApp->pharmacy_name,
                'owner_name'              => $mainApp->owner_name,
                'phone'                   => $mainApp->phone,
                'city'                    => $mainApp->city,
                'address'                 => $mainApp->address,
                'working_hours'           => $mainApp->working_hours,
                'license_number'          => $mainApp->license_number,
                'license_document'        => $mainApp->license_document,
                'lat'                     => $mainApp->lat,
                'lng'                     => $mainApp->lng,
                'services'                => $mainApp->services,
                'has_collaboration'       => $mainApp->has_collaboration,
                'is_active'               => true,
                'is_big_pharmacy'         => true,
            ]
        );

        // تمرير كولكشن الأدوية بالكامل لربطها بالصيدلية
        $this->attachRandomMedicines($mainPharmacy, $allMedicines, $faker);

        foreach ($applicationsQueue as $queueItem) {
            $provider = $faker->randomElement($providers);
            $cityName = $queueItem['city'];
            $randomStatus = $queueItem['status'];

            $latOffset = $faker->numberBetween(-80000, 80000) / 1000000;
            $lngOffset = $faker->numberBetween(-80000, 80000) / 1000000;
            $lat = $egyptCities[$cityName]['lat'] + $latOffset;
            $lng = $egyptCities[$cityName]['lng'] + $lngOffset;

            $user = User::create([
                'name'              => $faker->name,
                'email'             => $faker->unique()->safeEmail,
                'phone'             => $faker->unique()->numerify('01#########'),
                'password'          => $provider ? null : Hash::make('password'),
                'role'              => 'pharmacy',
                'provider_type'     => $provider,
                'provider_id'       => $provider ? Str::uuid() : null,
                'email_verified_at' => $faker->boolean(80) ? now() : null,
                'latitude'          => $lat,
                'longitude'         => $lng,
            ]);

            $randomServices = $faker->randomElements($servicesList, $faker->numberBetween(1, 3));

            $application = PharmacyApplication::create([
                'user_id'           => $user->id,
                'pharmacy_name'     => 'صيدلية ' . $faker->lastName,
                'owner_name'        => $user->name,
                'phone'             => $user->phone,
                'email'             => $user->email,
                'city'              => $cityName,
                'address'           => $faker->address,
                'lat'               => $lat,
                'lng'               => $lng,
                'working_hours'     => $faker->randomElement(['24/7', '08:00 AM - 12:00 AM', '10:00 AM - 10:00 PM']),
                'license_number'    => 'PH-' . $faker->numerify('#####'),
                'license_document'  => 'licenses/sample.pdf',
                'services'          => $randomServices,
                'status'            => $randomStatus,
                'has_collaboration' => $faker->boolean,
                'admin_notes'       => $randomStatus === 'rejected' ? $faker->randomElement(['صورة الترخيص غير واضحة', 'رقم الهاتف غير متاح', 'يرجى إرفاق بطاقة الهوية للتأكيد']) : null,
                'created_at'        => $faker->dateTimeBetween('-6 months', 'now'),
            ]);

            if ($randomStatus === 'approved') {
                $pharmacy = Pharmacy::create([
                    'user_id'                 => $user->id,
                    'pharmacy_application_id' => $application->id,
                    'pharmacy_name'           => $application->pharmacy_name,
                    'owner_name'              => $application->owner_name,
                    'phone'                   => $application->phone,
                    'email'                   => $application->email,
                    'city'                    => $application->city,
                    'address'                 => $application->address,
                    'working_hours'           => $application->working_hours,
                    'license_number'          => $application->license_number,
                    'license_document'        => $application->license_document,
                    'lat'                     => $application->lat,
                    'lng'                     => $application->lng,
                    'services'                => $application->services,
                    'has_collaboration'       => $application->has_collaboration,
                    'is_active'               => $faker->boolean(85),
                    'is_big_pharmacy'         => $faker->boolean(30),
                    'created_at'              => $application->created_at,
                ]);

                $this->attachRandomMedicines($pharmacy, $allMedicines, $faker);
            }
        }
    }

    /**
     * دالة مساعدة لربط الأدوية بالصيدلية بالأسعار والكميات العشوائية
     */
    private function attachRandomMedicines($pharmacy, $allMedicines, $faker)
    {
        if ($allMedicines->count() < 25) {
            return;
        }

        // تحويل الكولكشن إلى مصفوفة لسحب عناصر عشوائية منها
        $randomMedicines = $faker->randomElements($allMedicines->toArray(), 25);
        $syncData = [];

        foreach ($randomMedicines as $med) {
            $quantity = $faker->numberBetween(0, 100);

            $status = 'available';
            if ($quantity === 0) {
                $status = 'out_of_stock';
            } elseif ($faker->boolean(10)) {
                $status = 'hidden';
            }

            // ✨ المنطق الجديد للتسعير
            if ($med['is_price_fixed']) {
                // إذا كان الدواء تسعيرته إجبارية، يجب أن يكون سعر البيع هو السعر الرسمي بالضبط
                $sellingPrice = $med['official_price'];
            } else {
                // إذا لم يكن إجبارياً (مثل الفيتامينات)، الصيدلية يمكنها البيع بالسعر الرسمي أو عمل خصم
                $sellingPrice = $faker->boolean(70)
                    ? $med['official_price']
                    : round($med['official_price'] * $faker->randomFloat(2, 0.85, 0.95), 2);
            }

            $syncData[$med['id']] = [
                'price'    => $sellingPrice,
                'quantity' => $quantity,
                'status'   => $status,
            ];
        }

        $pharmacy->medicines()->syncWithoutDetaching($syncData);
    }
}
