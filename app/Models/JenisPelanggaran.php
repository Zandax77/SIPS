<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPelanggaran extends Model
{
    protected $table = 'jenis_pelanggarans';
    protected $fillable = ['id_kategori_pelanggaran','nama','deskripsi'];

    public function kategori_pelanggaran()
    {
        return $this->belongsTo('App\Models\KategoriPelanggaran');
    }
}
