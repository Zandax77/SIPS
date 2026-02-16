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
        Schema::table('petugas', function (Blueprint $table) {
            // Add role column: 'admin' or 'petugas'
            $table->enum('role', ['admin', 'petugas'])->default('petugas')->after('jabatan');

            // Add status column: 'active', 'inactive', or 'blocked'
            // active: can login, inactive: waiting for admin activation, blocked: cannot login
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('inactive')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->dropColumn(['role', 'status']);
        });
    }
};

