<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPelanggaran extends Model
{
    protected $table = 'kategori_pelanggarans';
    protected $fillable = ['nama','poin'];

    public function pelanggaran()
    {
        return $this->hasMany('App\Models\Pelanggaran');
    }
}
