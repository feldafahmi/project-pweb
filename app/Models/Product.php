<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'type',
        'title',
        'description',
        'learnings',
        'includes',
        'rating',
        'win_rate',
        'total_pages',
        'students',
        'price',
        'original_price',
        'duration',
        'is_featured',
        'is_bestseller',
        'image_url',
        'author_id',
    ];

    protected $casts = [
        'rating'        => 'float',
        'is_featured'   => 'boolean',
        'is_bestseller' => 'boolean',
        'learnings'     => 'array',
        'includes'      => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(Mentor::class, 'author_id');
    }

    public function curriculumSections()
    {
        return $this->hasMany(CurriculumSection::class)->orderBy('sort_order');
    }

    public function chapters()
    {
        return $this->hasMany(ProductChapter::class)->orderBy('sort_order');
    }

    public function batches()
    {
        return $this->hasMany(BootcampBatch::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->with('user:id,name')->latest();
    }
}
