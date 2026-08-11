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
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_petugas');
            $table->string('nama_prestasi');
            $table->enum('bidang', ['Akademik', 'Non Akademik']);
            $table->enum('tingkat', ['Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional']);
            $table->string('peringkat')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('pengurangan_poin')->default(0)->comment('Jumlah poin pelanggaran yang dikurangi karena prestasi ini');
            $table->string('bukti_foto')->nullable();
            $table->date('tanggal_prestasi');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_siswa')->references('id')->on('siswas')->onDelete('cascade');
            $table->foreign('id_petugas')->references('id')->on('petugas')->onDelete('cascade');

            // Indexes
            $table->index('id_siswa');
            $table->index('bidang');
            $table->index('tingkat');
            $table->index('tanggal_prestasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};

