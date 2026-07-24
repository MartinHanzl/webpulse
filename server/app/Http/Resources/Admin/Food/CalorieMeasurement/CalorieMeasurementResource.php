<?php

namespace App\Http\Resources\Admin\Food\CalorieMeasurement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalorieMeasurementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->items ?? [];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'portions' => $this->portions,
            'items' => $items,
            'items_count' => count($items),
            'sites' => $this->sites,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
