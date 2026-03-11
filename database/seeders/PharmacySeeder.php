<?php

namespace Database\Seeders;

use App\Models\PharmacyApplication;
use App\Models\User;
use Illuminate\Database\Seeder;

class PharmacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/PharmacySeeder.php
    public function run()
    {
        $user = User::create([
            'name' => 'Mohamed Sayed',
            'email' => 'pharmacy@medinear.com',
            'password' => bcrypt('password'),
        ]);

        PharmacyApplication::create([
            'user_id' => $user->id,
            'pharmacy_name' => 'MediNear Central',
            'owner_name' => 'Mohamed Sayed',
            'phone' => '+20123456789',
            'email' => 'pharmacy@medinear.com',
            'city' => 'Beni Suef',
            'address' => 'Main St, City Center',
            'lat' => 29.0661,
            'lng' => 31.0994,
            'working_hours' => '24/7',
            'license_number' => 'PH-12345',
            'license_document' => 'licenses/sample.pdf',
            'services' => ['24_hours', 'delivery'],
            'status' => 'under_review'
        ]);
    }
}
