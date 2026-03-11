<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\Category;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::first();

        if ($category) {
            $medicines = [
                [
                    'name' => 'بانادول اكسترا 500 ملجم',
                    'description' => 'مسكن للألم وخافض للحرارة سريع المفعول.',
                    'category_id' => $category->id,
                    'status' => 1,
                ],
                [
                    'name' => 'أوجمنتين 1 جم',
                    'description' => 'مضاد حيوي واسع المجال لعلاج العدوى البكتيرية.',
                    'category_id' => $category->id,
                    'status' => 1,
                ],
                [
                    'name' => 'بروفين 400 ملجم',
                    'description' => 'مسكن قوي للآلام ومضاد للالتهابات.',
                    'category_id' => $category->id,
                    'status' => 0,
                ],
            ];

            foreach ($medicines as $medicine) {
                Medicine::create($medicine);
            }
        }
    }
}
