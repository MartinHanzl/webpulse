<?php

namespace App\Http\Resources\User;

use App\Http\Resources\QickAccess\QuickAccessResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'quick_access' => QuickAccessResource::collection($this->quickAccess),
        ];
    }
}
