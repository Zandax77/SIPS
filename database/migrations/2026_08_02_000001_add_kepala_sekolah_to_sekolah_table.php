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
        Schema::table('sekolah', function (Blueprint $table) {
            $table->string('nama_kepala_sekolah')->nullable()->after('logo_sekolah');
            $table->string('nip_kepala_sekolah', 30)->nullable()->after('nama_kepala_sekolah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropColumn(['nama_kepala_sekolah', 'nip_kepala_sekolah']);
        });
    }
};

