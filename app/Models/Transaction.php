<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'TRANSACTIONS';
    protected $primaryKey = 'ID_TRANSACTIONS';
    public $timestamps = false;

    protected $fillable = [
        'ID_USERS', 
        'ID_PACKAGE', 
        'TOTAL_AMOUNT', 
        'PAYMENT_STATUS', 
        'PAYMENT_METHOD', 
        'TANGGAL_PEMBELIAN', 
        'BUKTI_TRANSFER'
    ];
}
