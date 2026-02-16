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
            $table->string('password_reset_token')->nullable()->after('password');
            $table->timestamp('password_reset_expires')->nullable()->after('password_reset_token');
            $table->timestamp('password_changed_at')->nullable()->after('password_reset_expires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->dropColumn(['password_reset_token', 'password_reset_expires', 'password_changed_at']);
        });
    }
};

