<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurriculumItem extends Model
{
    protected $fillable = ['curriculum_section_id', 'title', 'duration', 'type', 'sort_order'];

    public function section()
    {
        return $this->belongsTo(CurriculumSection::class, 'curriculum_section_id');
    }
}
