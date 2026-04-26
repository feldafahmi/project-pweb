<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BootcampBatch extends Model
{
    protected $fillable = ['product_id', 'label', 'date_range', 'spots', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
