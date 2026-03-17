<?php

namespace Database\Seeders;

use App\Models\DeliveryCompany;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DeliveryCompanySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ar_EG');

        $companies = ['أرامكس', 'مرسول', 'بوسطة', 'فيديكس', 'ساعي', 'طلبات', 'دي إتش إل'];

        foreach ($companies as $company) {
            DeliveryCompany::create([
                'name'   => $company . ' للتوصيل',
                'email'  => 'contact@' . strtolower(str_replace(' ', '', $faker->unique()->word)) . '.com',
                'phone'  => $faker->unique()->numerify('01#########'),
                'status' => $faker->boolean(80) ? 1 : 0, // 80% نشط
            ]);
        }
    }
}
