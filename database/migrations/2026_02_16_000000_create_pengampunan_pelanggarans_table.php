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
        Schema::create('pengampunan_pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pelanggaran');
            $table->integer('id_siswa');
            $table->integer('id_petugas'); // Petugas yang melakukan pengampunan/pengurangan
            $table->enum('tipe', ['pengampunan', 'pengurangan_poin']); // Jenis tindakan
            $table->integer('poin_asli'); // Poin pelanggaran asli
            $table->integer('poin_dikurangi')->default(0); // Poin yang dikurangi (0 untuk pengampunan penuh)
            $table->string('alasan'); // Alasan pengampunan/pengurangan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengampunan_pelanggarans');
    }
};

