<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductChapter extends Model
{
    protected $fillable = ['product_id', 'chapter_number', 'title', 'page_range', 'is_free', 'sort_order'];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
