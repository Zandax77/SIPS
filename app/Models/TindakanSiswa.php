<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TindakanSiswa extends Model
{
    protected $table = 'tindakan_siswas';
    protected $fillable = [
        'id_siswa',
        'id_petugas',
        'jenis_tindakan',
        'deskripsi_tindakan',
        'hasil_tindakan',
        'catatan_hasil',
        'tanggal_tindakan'
    ];

    /**
     * Get the student associated with this action
     */
    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa');
    }

    /**
     * Get the officer who recorded this action
     */
    public function petugas()
    {
        return $this->belongsTo('App\Models\Petugas', 'id_petugas');
    }

    /**
     * Check if there's an unresolved action (still in progress or failed)
     */
    public static function hasUnresolvedAction($siswaId)
    {
        return self::where('id_siswa', $siswaId)
            ->whereIn('hasil_tindakan', ['Tidak Berhasil', 'Sedang Berlangsung'])
            ->exists();
    }

    /**
     * Get the latest action for a student
     */
    public static function getLatestAction($siswaId)
    {
        return self::where('id_siswa', $siswaId)
            ->orderBy('tanggal_tindakan', 'desc')
            ->first();
    }

    /**
     * Get all actions for a student
     */
    public static function getActionsForSiswa($siswaId)
    {
        return self::where('id_siswa', $siswaId)
            ->orderBy('tanggal_tindakan', 'desc')
            ->get();
    }
}

