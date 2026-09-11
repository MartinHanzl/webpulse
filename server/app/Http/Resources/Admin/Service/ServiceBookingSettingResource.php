<?php

namespace App\Http\Resources\Admin\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceBookingSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'service_id' => $this->service_id,
            'service_name' => $this->service?->name,
            'work_start' => $this->work_start,
            'work_end' => $this->work_end,
            'slot_duration_minutes' => $this->slot_duration_minutes,
            'break_minutes' => $this->break_minutes,
            'working_days' => $this->working_days,
            'active' => $this->active,
            'exceptions' => $this->whenLoaded('exceptions', function () {
                return $this->exceptions->map(function ($exception) {
                    return [
                        'id' => $exception->id,
                        'date' => $exception->date?->format('Y-m-d'),
                        'is_closed' => $exception->is_closed,
                        'custom_times' => $exception->custom_times,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
