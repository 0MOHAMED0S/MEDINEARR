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
        Schema::create('packet_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('packet_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['image', 'note', 'medicine']);
            $table->text('note')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('medicine_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packet_items');
    }
};
