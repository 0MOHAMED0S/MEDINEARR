<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Pharmacy;
use App\Models\Medicine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $pharmacies = Pharmacy::all();
        $medicines = Medicine::all();

        if ($users->isEmpty() || $pharmacies->isEmpty() || $medicines->isEmpty()) {
            $this->command->warn('Cannot seed Orders. Please ensure Users, Pharmacies, and Medicines are seeded first.');
            return;
        }

        $statuses = ['pending', 'accepted', 'preparing', 'out_for_delivery', 'delivered', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];
        $paymentMethods = ['cash', 'paymob'];

        $this->command->info('Seeding 50 Orders...');

        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $pharmacy = $pharmacies->random();

            // Random items count (1 to 4)
            $itemsCount = rand(1, 4);
            $orderMedicines = $medicines->random($itemsCount);

            $subTotal = 0;
            $itemsData = [];

            foreach ($orderMedicines as $medicine) {
                $quantity = rand(1, 3);
                $price = $medicine->official_price > 0 ? $medicine->official_price : rand(20, 200);
                
                $itemsData[] = [
                    'medicine_id' => $medicine->id,
                    'quantity'    => $quantity,
                    'price'       => $price,
                ];

                $subTotal += ($price * $quantity);
            }

            $deliveryFee = 15.00;
            $discount = 0;
            
            // Randomly apply discount 20% of the time
            if (rand(1, 100) <= 20) {
                $discount = rand(10, 50);
                if ($discount > $subTotal) {
                    $discount = $subTotal;
                }
            }

            $grandTotal = ($subTotal - $discount) + $deliveryFee;
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

            // Random date in the last 60 days
            $createdAt = now()->subDays(rand(0, 60))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            // Create Order
            $order = Order::create([
                'order_reference' => 'ORD-' . strtoupper(Str::random(10)),
                'user_id'         => $user->id,
                'pharmacy_id'     => $pharmacy->id,
                'coupon_id'       => null,
                'sub_total'       => $subTotal,
                'discount'        => $discount,
                'delivery_fee'    => $deliveryFee,
                'grand_total'     => $grandTotal,
                'payment_method'  => $paymentMethod,
                'payment_status'  => $paymentStatuses[array_rand($paymentStatuses)],
                'status'          => $statuses[array_rand($statuses)],
                'phone'           => $user->phone ?? '01000000000',
                'address'         => 'Test Address ' . rand(1, 100),
                'notes'           => rand(0, 1) ? 'Please deliver quickly' : null,
                'created_at'      => $createdAt,
                'updated_at'      => $createdAt,
            ]);

            // Create Order Items
            foreach ($itemsData as $item) {
                OrderItem::create([
                    'order_id'    => $order->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity'    => $item['quantity'],
                    'price'       => $item['price'],
                    'created_at'  => $createdAt,
                    'updated_at'  => $createdAt,
                ]);
            }

            // Update Pharmacy Wallet if delivered
            if ($order->status === 'delivered') {
                $wallet = \App\Models\PharmacyWallet::firstOrCreate(
                    ['pharmacy_id' => $pharmacy->id]
                );
                
                // Add the grand total to their available balance and total earnings
                $wallet->balance += $grandTotal;
                $wallet->total_earned += $grandTotal;
                $wallet->save();
            }
        }

        $this->command->info('Orders seeded successfully!');
    }
}
