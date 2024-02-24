<?php

namespace App\Http\Resources\Admin\Settings;

use App\Models\Settings\LinkTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var LinkTranslation $translation */
        $translation = optional($this->getTranslation());
        return [
            'id' => $this->id,
            'is_active' => $this->is_active,
            'title' => $translation->title,
            'link' => $translation->link,
            'translations' => array_column($this->translations->toArray(), NULL, 'locale'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
