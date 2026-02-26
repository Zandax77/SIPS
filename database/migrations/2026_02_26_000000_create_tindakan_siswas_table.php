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
        Schema::create('tindakan_siswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_petugas');
            $table->string('jenis_tindakan'); // Jenis tindakan: "Panggilan Orang Tua", "Surat Peringatan", "Skorsing", "Pertemuan BK", "PBK", "MoU", dll
            $table->text('deskripsi_tindakan')->nullable(); // Detail tindakan yang diambil
            $table->enum('hasil_tindakan', ['Berhasil', 'Tidak Berhasil', 'Perlu Evaluasi', 'Sedang Berlangsung'])->default('Sedang Berlangsung');
            $table->text('catatan_hasil')->nullable(); // Catatan hasil tindakan
            $table->date('tanggal_tindakan');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_siswa')->references('id')->on('siswas')->onDelete('cascade');
            $table->foreign('id_petugas')->references('id')->on('petugas')->onDelete('cascade');
            
            // Index for faster queries
            $table->index('id_siswa');
            $table->index('tanggal_tindakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakan_siswas');
    }
};

