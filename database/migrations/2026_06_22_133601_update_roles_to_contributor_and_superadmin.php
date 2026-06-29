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
        if (Schema::hasTable('users')) {
            // Update enum schema for MySQL
            \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('member', 'contributor', 'admin', 'superadmin') NOT NULL DEFAULT 'member'");

            // Update existing data
            \DB::table('users')
                ->where('role', 'chef')
                ->update(['role' => 'contributor']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
