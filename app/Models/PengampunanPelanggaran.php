<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengampunanPelanggaran extends Model
{
    protected $table = 'pengampunan_pelanggarans';
    protected $fillable = [
        'id_pelanggaran',
        'id_siswa',
        'id_petugas',
        'tipe',
        'poin_asli',
        'poin_dikurangi',
        'alasan'
    ];

    public function pelanggaran()
    {
        return $this->belongsTo('App\Models\Pelanggaran', 'id_pelanggaran');
    }

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa');
    }

    public function petugas()
    {
        return $this->belongsTo('App\Models\Petugas', 'id_petugas');
    }
}

