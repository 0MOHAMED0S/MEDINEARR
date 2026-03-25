<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\SearchHistory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SearchHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_EG');

        // جلب البيانات المتاحة في قاعدة البيانات لربطها
        $userIds = User::where('role', 'user')->pluck('id')->toArray();
        $medicineIds = Medicine::pluck('id')->toArray();
        $pharmacyIds = Pharmacy::pluck('id')->toArray();

        // التأكد من وجود بيانات قبل البدء
        if (empty($medicineIds) || empty($pharmacyIds)) {
            $this->command->warn('يرجى تشغيل MedicineSeeder و PharmacySeeder أولاً.');
            return;
        }

        // كلمات بحث واقعية عن الصيدليات
        $pharmacySearchQueries = [
            'صيدلية', 'العزبي', 'سيف', 'رشدي', 'الشفاء', 'صيدلية مفتوحة',
            'صيدلية توصيل', 'خليل', 'مصر', 'الطرشوبي', 'علي وعلي', 'نور'
        ];

        // توليد 300 عملية بحث عشوائية للتحليلات (يمكنك زيادة الرقم كما تحب)
        $totalSearches = 300;

        for ($i = 0; $i < $totalSearches; $i++) {

            // 1. تحديد من قام بالبحث (80% مستخدم مسجل، 20% زائر غير مسجل)
            $userId = ($faker->boolean(80) && !empty($userIds)) ? $faker->randomElement($userIds) : null;

            // 2. إحداثيات البحث (مواقع عشوائية داخل مصر)
            $lat = $faker->randomFloat(8, 24.0, 31.5);
            $lng = $faker->randomFloat(8, 25.0, 34.0);

            // 3. تحديد نوع البحث (70% أدوية، 30% صيدليات)
            $searchType = $faker->boolean(70) ? 'medicine' : 'pharmacy';

            $searchQuery = null;
            $medicineId = null;

            if ($searchType === 'pharmacy') {
                $searchQuery = $faker->randomElement($pharmacySearchQueries);
            } else {
                $medicineId = $faker->randomElement($medicineIds);
            }

            // 4. توليد النتائج (كم صيدلية ظهرت في البحث؟)
            // 15% من الأبحاث تفشل (0 نتائج)، والباقي ينجح ويرجع بين 1 إلى 25 نتيجة
            $resultsCount = $faker->boolean(15) ? 0 : $faker->numberBetween(1, 25);

            $returnedIds = [];
            if ($resultsCount > 0) {
                // نأخذ 10 صيدليات كحد أقصى (كما حددنا في الـ Controller)
                $limit = min(5, $resultsCount);
                $returnedIds = $faker->randomElements($pharmacyIds, $limit);
            }

            // 5. توزيع تواريخ البحث على آخر 3 شهور لتبدو الرسوم البيانية (Charts) منطقية
            $createdAt = $faker->dateTimeBetween('-3 months', 'now');

            // 6. إنشاء السجل
            SearchHistory::create([
                'user_id'               => $userId,
                'search_type'           => $searchType,
                'search_query'          => $searchQuery,
                'medicine_id'           => $medicineId,
                'lat'                   => $lat,
                'lng'                   => $lng,
                'results_count'         => $resultsCount,
                'returned_pharmacy_ids' => $returnedIds,
                'created_at'            => $createdAt,
                'updated_at'            => $createdAt,
            ]);
        }
    }
}
