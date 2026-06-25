<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductChapter extends Model
{
    protected $fillable = ['product_id', 'chapter_number', 'title', 'page_range', 'file_url', 'is_free', 'sort_order'];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    // Disembunyikan dari endpoint publik; hanya dikeluarkan lewat /products/{id}/content.
    protected $hidden = ['file_url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
