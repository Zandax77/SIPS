<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support ALTER COLUMN, so we need to recreate the table
        // Step 1: Create temporary table with nullable deskripsi
        DB::statement('CREATE TABLE pelotmp_pelanggarans (
            id INTEGER PRIMARY KEY,
            id_siswa INTEGER NOT NULL,
            id_jenis_pelanggaran INTEGER NOT NULL,
            deskripsi VARCHAR(255),
            id_petugas INTEGER NOT NULL,
            created_at DATETIME,
            updated_at DATETIME,
            lampiran VARCHAR(255),
            tipe_lampiran VARCHAR(50)
        )');

        // Step 2: Copy data from original table
        DB::statement('INSERT INTO pelotmp_pelanggarans SELECT * FROM pelanggarans');

        // Step 3: Drop original table
        DB::statement('DROP TABLE pelanggarans');

        // Step 4: Rename temp table to original name
        DB::statement('ALTER TABLE pelotmp_pelanggarans RENAME TO pelanggarans');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse: recreate table with NOT NULL deskripsi
        DB::statement('CREATE TABLE pelotmp_pelanggarans (
            id INTEGER PRIMARY KEY,
            id_siswa INTEGER NOT NULL,
            id_jenis_pelanggaran INTEGER NOT NULL,
            deskripsi VARCHAR(255) NOT NULL,
            id_petugas INTEGER NOT NULL,
            created_at DATETIME,
            updated_at DATETIME,
            lampiran VARCHAR(255),
            tipe_lampiran VARCHAR(50)
        )');

        DB::statement('INSERT INTO pelotmp_pelanggarans SELECT id, id_siswa, id_jenis_pelanggaran, COALESCE(deskripsi, ""), id_petugas, created_at, updated_at, lampiran, tipe_lampiran FROM pelanggarans');

        DB::statement('DROP TABLE pelanggarans');

        DB::statement('ALTER TABLE pelotmp_pelanggarans RENAME TO pelanggarans');
    }
};

