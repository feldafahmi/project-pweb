<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurriculumItem extends Model
{
    protected $fillable = ['curriculum_section_id', 'title', 'duration', 'type', 'content_url', 'is_free', 'sort_order'];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    // Disembunyikan dari serialisasi default (endpoint publik /products/{id}).
    // URL konten asli hanya dikeluarkan lewat GET /products/{id}/content
    // dengan makeVisible() untuk user yang sudah membeli.
    protected $hidden = ['content_url'];

    public function section()
    {
        return $this->belongsTo(CurriculumSection::class, 'curriculum_section_id');
    }
}
