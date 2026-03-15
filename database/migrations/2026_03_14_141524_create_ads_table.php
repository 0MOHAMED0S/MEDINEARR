<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['banner', 'cover'])->default('banner');
            $table->string('title');
            $table->string('description')->nullable(); // للبانر فقط
            $table->string('image')->nullable(); // يحمل اللوجو أو الغلاف
            $table->string('link')->nullable();
            $table->string('bg_color')->default('#0f172a')->nullable(); // للبانر
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete(); // ربط اختياري
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ads');
    }
};
