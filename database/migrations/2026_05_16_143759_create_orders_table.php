<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_reference')->unique();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();

            // ✨ تم إضافة حقل لربط الطلب بالكوبون المستخدم (إن وجد) ✨
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();

            // الحسابات المالية
            $table->decimal('sub_total', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);

            // الدفع والتوصيل
            $table->enum('payment_method', ['cash', 'paymob']);
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('status', [
                'pending',
                'accepted',
                'preparing',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ])->default('pending');

            // حقول Paymob
            $table->string('paymob_order_id')->nullable();
            $table->string('paymob_transaction_id')->nullable();

            // معلومات العميل
            $table->string('phone');
            $table->string('address');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
