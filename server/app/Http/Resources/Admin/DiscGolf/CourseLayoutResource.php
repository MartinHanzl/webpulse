<?php

namespace App\Http\Resources\Admin\DiscGolf;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseLayoutResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'name' => $this->name,
            'total_meters' => $this->total_meters,
            'holes_count' => $this->holes_count,
            'par' => $this->par,
            'position' => $this->position,
            'holes' => CourseLayoutHoleResource::collection($this->whenLoaded('holes')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
