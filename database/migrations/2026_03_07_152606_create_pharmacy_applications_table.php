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
        Schema::create('pharmacy_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('pharmacy_name');
            $table->string('owner_name');
            $table->string('phone');
            $table->string('email');
            $table->string('city');
            $table->string('address');
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('working_hours');
            $table->string('license_number');
            $table->string('license_document');
            $table->json('services')->nullable();
            $table->boolean('has_collaboration')->default(false);
            $table->string('image')->nullable();
            $table->enum('status', ['under_review', 'approved', 'rejected'])->default('under_review');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_applications');
    }
};
