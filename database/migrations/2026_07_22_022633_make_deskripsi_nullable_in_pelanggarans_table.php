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
        // SQLite does not support modifying columns directly.
        // This migration is a placeholder to document the change.
        // The actual nullable change is applied in the create table migration.
        // For existing databases, add a raw SQL approach.
        
        Schema::table('pelanggarans', function (Blueprint $table) {
            // SQLite workaround: recreate the column as nullable
            $table->string('deskripsi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggarans', function (Blueprint $table) {
            $table->string('deskripsi')->change();
        });
    }
};

