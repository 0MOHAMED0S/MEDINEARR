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
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pharmacy_application_id')->nullable()->constrained('pharmacy_applications')->nullOnDelete();
            $table->string('pharmacy_name');
            $table->string('owner_name');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('working_hours')->nullable();
            $table->string('license_number')->nullable();
            $table->string('image')->nullable();
            $table->string('license_document')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->json('services')->nullable();
            $table->boolean('has_collaboration')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_big_pharmacy')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
