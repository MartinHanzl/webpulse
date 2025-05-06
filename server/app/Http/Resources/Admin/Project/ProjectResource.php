<?php

namespace App\Http\Resources\Admin\Project;

use App\Http\Resources\Admin\Contact\ContactSimpleResource;
use App\Http\Resources\Admin\Currency\CurrencyResource;
use App\Http\Resources\Admin\TaxRate\TaxRateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'description' => $this->description,
            'note' => $this->note,
            'image' => $this->image,
            'hourly_rate' => $this->hourly_rate,
            'expected_price' => $this->expected_price,
            'expected_price_vat' => $this->expected_price_vat,
            'expected_hours' => $this->expected_hours,
            'total_price' => $this->total_price,
            'total_price_vat' => $this->total_price_vat,
            'total_hours' => $this->total_hours,
            'invoice_firstname' => $this->invoice_firstname,
            'invoice_lastname' => $this->invoice_lastname,
            'invoice_ico' => $this->invoice_ico,
            'invoice_dic' => $this->invoice_dic,
            'invoice_email' => $this->invoice_email,
            'invoice_phone_prefix' => $this->invoice_phone_prefix,
            'invoice_phone' => $this->invoice_phone,
            'invoice_street' => $this->invoice_street,
            'invoice_city' => $this->invoice_city,
            'invoice_zip' => $this->invoice_zip,
            'invoice_country_id' => $this->invoice_country_id,
            'is_delivery_address_same' => $this->is_delivery_address_same,
            'delivery_firstname' => $this->delivery_firstname,
            'delivery_lastname' => $this->delivery_lastname,
            'delivery_email' => $this->delivery_email,
            'delivery_phone_prefix' => $this->delivery_phone_prefix,
            'delivery_phone' => $this->delivery_phone,
            'delivery_street' => $this->delivery_street,
            'delivery_city' => $this->delivery_city,
            'delivery_zip' => $this->delivery_zip,
            'delivery_country_id' => $this->delivery_country_id,
            'status_id' => $this->status_id,
            'tax_rate_id' => $this->tax_rate_id,
            'tax_rate' => TaxRateResource::make($this->taxRate),
            'client_id' => $this->client_id,
            'client' => ContactSimpleResource::make($this->client),
            'events' => $this->events,
            'start_date' => $this->start_date,
            'formatted_start_date' => $this->start_date?->format('Y-m-d H:i:s'),
            'end_date' => $this->end_date,
            'formatted_end_date' => $this->end_date?->format('Y-m-d H:i:s'),
            'currency_id' => $this->currency_id,
            'currency' => CurrencyResource::make($this->currency),
        ];
    }
}
