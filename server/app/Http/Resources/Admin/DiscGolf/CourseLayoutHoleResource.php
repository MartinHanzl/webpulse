<?php

namespace App\Http\Resources\Admin\DiscGolf;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseLayoutHoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course_layout_id' => $this->course_layout_id,
            'number' => $this->number,
            'par' => $this->par,
            'meters' => $this->meters,
        ];
    }
}
