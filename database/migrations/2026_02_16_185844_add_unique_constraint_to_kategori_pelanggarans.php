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
        Schema::table('kategori_pelanggarans', function (Blueprint $table) {
            $table->unique(['nama', 'poin'], 'kategori_nama_poin_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_pelanggarans', function (Blueprint $table) {
            $table->dropUnique('kategori_nama_poin_unique');
        });
    }
};
