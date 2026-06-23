<?php

namespace Database\Seeders;

use App\Models\PharmacyWallet;
use App\Models\WithdrawalRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WithdrawalRequestSeeder extends Seeder
{
    public function run(): void
    {
        $wallets = PharmacyWallet::where('balance', '>=', 100)->get();

        if ($wallets->isEmpty()) {
            $this->command->warn('No wallets with balance >= 100 found. Skipping WithdrawalRequest seeding.');
            return;
        }

        $paymentMethods = ['Vodafone Cash', 'InstaPay', 'Bank Account'];
        $statuses = ['pending', 'approved', 'rejected'];

        $this->command->info('Seeding Withdrawal Requests...');

        foreach ($wallets as $wallet) {
            // Only create withdrawal requests for 60% of eligible pharmacies
            if (rand(1, 100) > 60) {
                continue;
            }

            // Create 1 to 3 random withdrawal requests for this pharmacy
            $requestsCount = rand(1, 3);

            for ($i = 0; $i < $requestsCount; $i++) {
                // Stop if the balance dropped below the 100 EGP minimum during the loop
                if ($wallet->balance < 100) {
                    break;
                }

                // Request a random amount between 100 EGP and 80% of their current balance
                $amount = rand(100, max(100, (int) ($wallet->balance * 0.8)));
                $status = $statuses[array_rand($statuses)];

                DB::beginTransaction();
                try {
                    // Only deduct the balance if the request is pending or approved
                    // (If it was rejected, the money would have been returned to the balance)
                    if ($status === 'pending' || $status === 'approved') {
                        $wallet->balance -= $amount;
                        $wallet->save();
                    }

                    $createdAt = now()->subDays(rand(0, 30))->subHours(rand(0, 23));

                    WithdrawalRequest::create([
                        'pharmacy_id' => $wallet->pharmacy_id,
                        'amount' => $amount,
                        'status' => $status,
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'account_details' => 'Account Number / Phone: 010' . rand(10000000, 99999999),
                        'admin_notes' => $status === 'rejected' ? 'تفاصيل الحساب غير صحيحة، يرجى المراجعة.' : null,
                        'created_at' => $createdAt,
                        'updated_at' => $status === 'pending' ? $createdAt : $createdAt->copy()->addDays(rand(1, 2)),
                    ]);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                }
            }
        }

        $this->command->info('Withdrawal Requests seeded successfully!');
    }
}
