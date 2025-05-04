<?php

namespace App\Http\Resources\Admin\Language;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
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
            'active' => $this->active,
            'fullCode' => $this->fullCode,
            'name' => $this->name,
            'translations' => array_column($this->translations->toArray(), null, 'locale'),
        ];
    }
}
