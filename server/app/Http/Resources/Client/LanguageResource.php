<?php

namespace App\Http\Resources\Client;

use App\Models\LanguageTranslation;
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
        /** @var LanguageTranslation $translation */
        $translation = optional($this->getTranslation());
        return [
            'id' => $this->id,
            'code' => $this->default_locale,
            'name' => $translation->name,
            'translations' => array_column($this->translations->toArray(), NULL, 'locale'),
        ];
    }
}
