<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'type'           => $this->type,
            'title'          => $this->title,
            'price'          => $this->price,
            'original_price' => $this->original_price,
            'image_url'      => $this->image_url,
            'is_featured'    => $this->is_featured,
            'is_bestseller'  => $this->is_bestseller,
            'duration'       => $this->duration,
            'rating'         => $this->rating,
            'students'       => $this->students,
        ];
    }
}
