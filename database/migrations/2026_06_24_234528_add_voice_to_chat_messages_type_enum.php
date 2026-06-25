<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN type ENUM('text', 'image', 'file', 'voice') DEFAULT 'text'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN type ENUM('text', 'image', 'file') DEFAULT 'text'");
    }
};
