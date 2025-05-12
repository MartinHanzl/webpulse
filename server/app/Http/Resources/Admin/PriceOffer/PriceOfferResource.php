<?php

namespace App\Http\Resources\Admin\PriceOffer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'json' => $this->json,
            'total_hours' => $this->total_hours,
            'total_price' => $this->total_price,
            'total_price_vat' => $this->total_price_vat,
            'valid_to' => $this->valid_to,
        ];
    }
}
