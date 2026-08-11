<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'prestasis';

    protected $fillable = [
        'id_siswa',
        'id_petugas',
        'nama_prestasi',
        'bidang',
        'tingkat',
        'peringkat',
        'deskripsi',
        'pengurangan_poin',
        'bukti_foto',
        'tanggal_prestasi',
    ];

    /**
     * Get the student associated with this achievement
     */
    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa');
    }

    /**
     * Get the officer who recorded this achievement
     */
    public function petugas()
    {
        return $this->belongsTo('App\Models\Petugas', 'id_petugas');
    }

    /**
     * Get total pengurangan poin for a student
     */
    public static function getTotalPenguranganPoin($siswaId)
    {
        return self::where('id_siswa', $siswaId)
            ->sum('pengurangan_poin');
    }

    /**
     * Get all achievements for a student
     */
    public static function getPrestasiForSiswa($siswaId)
    {
        return self::where('id_siswa', $siswaId)
            ->orderBy('tanggal_prestasi', 'desc')
            ->get();
    }
}

