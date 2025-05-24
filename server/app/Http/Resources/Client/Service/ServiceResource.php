<?php

namespace App\Http\Resources\Client\Service;

use App\Http\Resources\Admin\Currency\CurrencyResource;
use App\Http\Resources\Admin\TaxRate\TaxRateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class ServiceResource extends JsonResource
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
            'type' => $this->type,
            'price_type' => $this->price_type,
            'price' => $this->price,
            'tax_rate_id' => $this->tax_rate_id,
            'tax_rate' => TaxRateResource::make($this->taxRate),
            'currency_id' => $this->currency_id,
            'currency' => CurrencyResource::make($this->currency),
            'image' => $this->image,
            'active' => $this->active,
            'name' => $this->name,
            'slug' => $this->translate(App::getLocale())->slug,
            'perex' => $this->translate(App::getLocale())->perex,
            'description' => $this->translate(App::getLocale())->description,
            'meta_title' => $this->translate(App::getLocale())->meta_title,
            'meta_description' => $this->translate(App::getLocale())->meta_description,
        ];
    }
}
