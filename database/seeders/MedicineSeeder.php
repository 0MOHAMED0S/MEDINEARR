<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        // 1. استخدام Faker باللغة الإنجليزية
        $faker = Faker::create('en_US');

        // 2. إنشاء الفئات (Categories) باللغة الإنجليزية أولاً
        $categoryNames = [
            'Painkillers & Fever',
            'Antibiotics',
            'Vitamins & Supplements',
            'First Aid',
            'Cold & Cough',
            'Digestive Health',
            'Allergy Relief',
            'Skin Care'
        ];

        $categories = [];
        foreach ($categoryNames as $name) {
            $categories[] = Category::firstOrCreate(['name' => $name]);
        }

        // 3. قوائم لتركيب أسماء أدوية واقعية بالإنجليزية
        $baseMedicines = [
            'Paracetamol', 'Ibuprofen', 'Amoxicillin', 'Aspirin', 'Loratadine',
            'Omeprazole', 'Cetirizine', 'Vitamin C', 'Vitamin D3', 'Zinc',
            'Magnesium', 'Panadol', 'Advil', 'Tylenol', 'Zyrtec', 'Claritin',
            'Azithromycin', 'Ciprofloxacin', 'Metformin', 'Atorvastatin',
            'Amlodipine', 'Lisinopril', 'Losartan', 'Albuterol', 'Pantoprazole',
            'Gabapentin', 'Hydrochlorothiazide', 'Sertraline', 'Simvastatin',
            'Montelukast', 'Rosuvastatin', 'Escitalopram', 'Fluoxetine',
            'Doxycycline', 'Prednisone', 'Meloxicam', 'Clopidogrel'
        ];

        $forms = ['Tablets', 'Capsules', 'Syrup', 'Injection', 'Ointment', 'Drops', 'Gel', 'Cream'];
        $dosages = ['10mg', '20mg', '50mg', '100mg', '250mg', '400mg', '500mg', '800mg', '1g'];

        $createdCount = 0;

        // 4. توليد 100 دواء فريد وربطها بالفئات
        // استخدمنا while لضمان إدخال 100 دواء لا محالة، وتخطي الأسماء المكررة
        while ($createdCount < 100) {

            // سحب فئة عشوائية من الفئات التي تم إنشاؤها
            $randomCategory = $faker->randomElement($categories);

            // تركيب اسم دواء احترافي
            $medName = $faker->randomElement($baseMedicines) . ' ' .
                       $faker->randomElement($dosages) . ' ' .
                       $faker->randomElement($forms);

            // فحص هام جداً: التأكد من أن الاسم غير موجود مسبقاً لتجنب خطأ 1062 (Duplicate entry)
            if (!Medicine::where('name', $medName)->exists()) {

                Medicine::create([
                    'name'        => $medName,
                    'description' => $faker->realText(60), // توليد وصف إنجليزي واقعي من 60 حرف
                    'category_id' => $randomCategory->id,
                    'status'      => $faker->boolean(90) ? 1 : 0, // 90% نشط (1) و 10% غير نشط (0)
                ]);

                // زيادة العداد فقط عند نجاح إضافة دواء جديد غير مكرر
                $createdCount++;
            }
        }
    }
}
