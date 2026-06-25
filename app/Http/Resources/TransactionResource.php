<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'code'           => $this->code,
            'total_amount'   => $this->total_amount,
            'payment_method' => $this->payment_method,
            'status'         => $this->status,
            'snap_token'     => $this->snap_token,
            'payment_url'    => $this->payment_url,
            'midtrans_status' => $this->midtrans_status,
            'paid_at'        => optional($this->paid_at)->toIso8601String(),
            'created_at'     => optional($this->created_at)->toIso8601String(),
            'items_count'    => $this->whenCounted('items'),
            'items'          => TransactionItemResource::collection(
                $this->whenLoaded('items')
            ),
        ];
    }
}
