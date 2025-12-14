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
        // Drop existing role column constraint and recreate with panitia
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('jemaah', 'pengurus', 'admin', 'panitia') NOT NULL DEFAULT 'jemaah'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original roles (remove panitia)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('jemaah', 'pengurus', 'admin') NOT NULL DEFAULT 'jemaah'");
    }
};
