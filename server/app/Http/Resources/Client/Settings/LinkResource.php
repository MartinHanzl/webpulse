<?php

namespace App\Http\Resources\Client\Settings;

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
            'title' => $translation->title,
            'link' => $translation->link,
        ];
    }
}
