<?php

namespace App\Http\Resources\Admin\Language;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguagesTranslationResource extends JsonResource
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
            'language_id' => $this->language_id,
            'locale' => $this->locale,
            'name' => $this->name,
        ];
    }
}
