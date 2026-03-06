<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'PACKAGE';
    protected $primaryKey = 'ID_PACKAGE';
    public $timestamps = false;

    protected $fillable = [
        'NAMA_PAKET', 
        'KATEGORI', 
        'HARGA', 
        'TANGGAL_BELI'
    ];
}
