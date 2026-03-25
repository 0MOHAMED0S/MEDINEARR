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
        $faker = Faker::create('en_US');

        // 1. خريطة الأقسام المنطقية
        $logicalCategories = [
            'Painkillers & Fever' => [
                'is_fixed' => true,
                'medicines' => ['Paracetamol', 'Ibuprofen', 'Aspirin', 'Panadol', 'Advil', 'Tylenol', 'Meloxicam', 'Diclofenac']
            ],
            'Antibiotics' => [
                'is_fixed' => true,
                'medicines' => ['Amoxicillin', 'Azithromycin', 'Ciprofloxacin', 'Doxycycline', 'Cefuroxime', 'Clindamycin']
            ],
            'Cold & Cough' => [
                'is_fixed' => true,
                'medicines' => ['Guaifenesin', 'Dextromethorphan', 'Pseudoephedrine', 'Bromhexine']
            ],
            'Digestive Health' => [
                'is_fixed' => true,
                'medicines' => ['Omeprazole', 'Pantoprazole', 'Lansoprazole', 'Esomeprazole', 'Ranitidine']
            ],
            'Allergy Relief' => [
                'is_fixed' => true,
                'medicines' => ['Loratadine', 'Cetirizine', 'Zyrtec', 'Claritin', 'Fexofenadine']
            ],
            'Vitamins & Supplements' => [
                'is_fixed' => false,
                'medicines' => ['Vitamin C', 'Vitamin D3', 'Zinc', 'Magnesium', 'Omega 3', 'Calcium', 'Multivitamin', 'Iron']
            ],
            'Skin Care' => [
                'is_fixed' => false,
                'medicines' => ['Moisturizing Cream', 'Sunscreen', 'Acne Gel', 'Vitamin C Serum', 'Cleanser', 'Hyaluronic Acid']
            ],
            'First Aid' => [
                'is_fixed' => false,
                'medicines' => ['Antiseptic Liquid', 'Medical Bandage', 'Sterile Gauze', 'Burn Cream', 'Medical Tape']
            ]
        ];

        $forms = ['Tablets', 'Capsules', 'Syrup', 'Injection', 'Ointment', 'Drops', 'Gel', 'Cream'];
        $dosages = ['10mg', '20mg', '50mg', '100mg', '250mg', '400mg', '500mg', '800mg', '1g'];

        // 2. إنشاء الأقسام مسبقاً وتخزينها في مصفوفة لسهولة الوصول إليها
        $categoryModels = [];
        foreach ($logicalCategories as $categoryName => $data) {
            $categoryModels[$categoryName] = Category::firstOrCreate(['name' => $categoryName]);
        }

        $createdCount = 0;
        $categoryKeys = array_keys($logicalCategories); // استخراج أسماء الأقسام

        // 3. حلقة تضمن الوصول إلى 100 دواء بالضبط
        while ($createdCount < 100) {

            // اختيار قسم عشوائي في كل دورة
            $randomCategoryName = $faker->randomElement($categoryKeys);
            $categoryData = $logicalCategories[$randomCategoryName];
            $categoryModel = $categoryModels[$randomCategoryName];

            $baseName = $faker->randomElement($categoryData['medicines']);

            // تشكيل الاسم بناءً على القسم
            if (in_array($randomCategoryName, ['Skin Care', 'First Aid'])) {
                $medName = $baseName . ' ' . $faker->companySuffix; // أسماء تجارية للمستحضرات
            } else {
                $medName = $baseName . ' ' . $faker->randomElement($dosages) . ' ' . $faker->randomElement($forms);
            }

            // التأكد من عدم التكرار
            if (!Medicine::where('name', $medName)->exists()) {

                Medicine::create([
                    'name'           => $medName,
                    'description'    => $faker->realText(60),
                    'category_id'    => $categoryModel->id,
                    'official_price' => $categoryData['is_fixed'] ? $faker->randomFloat(2, 10, 200) : $faker->randomFloat(2, 50, 800),
                    'is_price_fixed' => $categoryData['is_fixed'],
                    'status'         => $faker->boolean(90) ? 1 : 0,
                ]);

                // نزيد العداد فقط عندما تتم عملية الإنشاء بنجاح
                $createdCount++;
            }
        }
    }
}
