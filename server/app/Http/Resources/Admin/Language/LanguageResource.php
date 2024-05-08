<?php

namespace App\Http\Resources\Admin\Language;

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
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'iso' => $this->iso,
            'active' => (bool)$this->active,
            //'translations' => LanguagesTranslationResource::collection($this->translations),
            'translations' => array_column($this->translations->toArray(), 'name', 'locale'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
