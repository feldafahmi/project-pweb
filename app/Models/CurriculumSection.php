<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurriculumSection extends Model
{
    protected $fillable = ['product_id', 'title', 'subtitle', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(CurriculumItem::class)->orderBy('sort_order');
    }
}
