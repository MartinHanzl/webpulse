<?php

namespace App\Http\Resources\Admin\Project;

use App\Http\Resources\Admin\Contact\ContactSimpleResource;
use App\Http\Resources\Admin\Currency\CurrencyResource;
use App\Http\Resources\Admin\TaxRate\TaxRateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSimpleResource extends JsonResource
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
            'name' => $this->name,
            'expected_price_vat' => $this->expected_price,
            'total_price_vat' => $this->total_price_vat,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'tax_rate' => TaxRateResource::make($this->taxRate),
            'client' => ContactSimpleResource::make($this->client),
            'status_name' => $this->status?->name,
            'status_color' => $this->status?->color,
            'updated_at' => $this->updated_at,
            'currency' => CurrencyResource::make($this->currency),
        ];
    }
}
