<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pharmacy_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();

            // الرصيد الحالي القابل للسحب (Balance)
            $table->decimal('balance', 10, 2)->default(0);

            // إجمالي كل اللي كسبته الصيدلية من يوم ما اشتركت في السيستم (Total Earnings)
            $table->decimal('total_earned', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_wallets');
    }
};
