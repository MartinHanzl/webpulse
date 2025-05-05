<?php

namespace App\Http\Resources\Admin\Country;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'iso' => $this->iso,
            'phone_prefix' => $this->phone_prefix,
            'active' => $this->active,
            'name' => $this->name,
            'translations' => array_column($this->translations->toArray(), null, 'locale'),
        ];
    }
}
