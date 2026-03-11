<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'أدوية ومسكنات',
                'description' => 'تشمل جميع أنواع المسكنات والأدوية العامة التي لا تحتاج لوصفة طبية.',
                'status' => 1,
            ],
            [
                'name' => 'عناية بالبشرة',
                'description' => 'منتجات التجميل، الكريمات، الغسول، ومرطبات الجلد.',
                'status' => 1,
            ],
            [
                'name' => 'فيتامينات ومكملات',
                'description' => 'مكملات غذائية، فيتامينات يومية، ومنتجات لرفع المناعة.',
                'status' => 1,
            ],
            [
                'name' => 'مستلزمات طبية',
                'description' => 'أجهزة قياس الضغط والسكر، حقن، وشاش طبي.',
                'status' => 0, // تصنيف متوقف للتجربة
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
