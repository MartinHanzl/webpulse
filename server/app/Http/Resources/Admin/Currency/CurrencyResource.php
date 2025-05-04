<?php

namespace App\Http\Resources\Admin\Currency;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
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
            'rate' => $this->rate,
            'decimals' => $this->decimals,
            'active' => $this->active,
            'bank_account_number' => $this->bank_account_number,
            'bank_account_name' => $this->bank_account_name,
            'bank_account_iban' => $this->bank_account_iban,
            'bank_account_swift' => $this->bank_account_swift,
            'name' => $this->name,
            'translations' => array_column($this->translations->toArray(), null, 'locale'),
        ];
    }
}
