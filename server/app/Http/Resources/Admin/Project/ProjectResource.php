<?php

namespace App\Http\Resources\Admin\Project;

use App\Http\Resources\Admin\Contact\ContactSimpleResource;
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
            'expected_price' => $this->expected_price,
            'total_price' => $this->total_price,
            'total_price_vat' => $this->total_price_vat,
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
            'invoice_country' => $this->invoice_country,
            'delivery_firstname' => $this->delivery_firstname,
            'delivery_lastname' => $this->delivery_lastname,
            'delivery_email' => $this->delivery_email,
            'delivery_phone_prefix' => $this->delivery_phone_prefix,
            'delivery_phone' => $this->delivery_phone,
            'delivery_street' => $this->delivery_street,
            'delivery_city' => $this->delivery_city,
            'delivery_zip' => $this->delivery_zip,
            'delivery_country' => $this->delivery_country,
            'tax_rate' => TaxRateResource::make($this->taxRate),
            'client' => ContactSimpleResource::make($this->client),
            'statuses' => $this->statuses,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
