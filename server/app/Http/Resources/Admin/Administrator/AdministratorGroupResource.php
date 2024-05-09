<?php

namespace App\Http\Resources\Admin\Administrator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdministratorGroupResource extends JsonResource
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
            'permissions' => $this->permissions,
        ];
    }
}
