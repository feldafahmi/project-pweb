<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'product_id'        => $this->product_id,
            'product_title'     => $this->product_title,
            'product_type'      => $this->product_type,
            'product_image_url' => $this->product_image_url,
            'price'             => $this->price,
            'quantity'          => $this->quantity,
            'subtotal'          => $this->subtotal,
        ];
    }
}
