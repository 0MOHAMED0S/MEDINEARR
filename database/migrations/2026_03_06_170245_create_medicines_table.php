<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description');
            $table->string('image')->nullable();

            // إضافة حقلي السعر وإجبارية السعر
            $table->decimal('official_price', 10, 2)->default(0);
            $table->boolean('is_price_fixed')->default(true);

            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
