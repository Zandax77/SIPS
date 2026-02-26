<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table = 'pelanggarans';
    protected $fillable = ['id_siswa','id_jenis_pelanggaran','deskripsi','id_petugas','bukti_foto'];


    public function jenis_pelanggaran()
{
    return $this->belongsTo('App\Models\JenisPelanggaran');
}

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa');
    }

    public function petugas()
    {
        return $this->belongsTo('App\Models\Petugas');
    }
}


