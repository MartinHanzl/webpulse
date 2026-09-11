<?php

namespace App\Services\Booking;

use App\Models\Service\Service;
use App\Models\Service\ServiceBooking;
use Carbon\Carbon;

class SlotGenerator
{
    /**
     * Generate available "HH:MM" booking slots for a service on a given date.
     */
    public function forServiceAndDate(Service $service, Carbon $date): array
    {
        $setting = $service->bookingSetting;

        if (! $setting || ! $setting->active) {
            return [];
        }

        $isoWeekday = (int) $date->isoWeekday();
        $workingDays = $setting->working_days ?: [1, 2, 3, 4, 5];

        if (! in_array($isoWeekday, $workingDays, true)) {
            return [];
        }

        $exception = $setting->exceptions()
            ->whereDate('date', $date->toDateString())
            ->first();

        if ($exception && $exception->is_closed) {
            return [];
        }

        if ($exception && $exception->custom_times) {
            $times = $exception->custom_times;
        } else {
            $times = $this->generateTimes($setting->work_start, $setting->work_end, $setting->slot_duration_minutes, $setting->break_minutes);
        }

        $bookedTimes = ServiceBooking::query()
            ->where('service_id', $service->id)
            ->where('date', $date->toDateString())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->pluck('time_from')
            ->map(fn ($time) => Carbon::parse($time)->format('H:i'))
            ->all();

        $times = array_values(array_diff($times, $bookedTimes));

        if ($date->isToday()) {
            $now = Carbon::now();
            $times = array_values(array_filter($times, function ($time) use ($date, $now) {
                $slotDateTime = Carbon::parse($date->toDateString().' '.$time);

                return $slotDateTime->greaterThan($now);
            }));
        }

        return $times;
    }

    /**
     * Generate a step-based list of "HH:MM" times between work_start and work_end.
     */
    protected function generateTimes(string $workStart, string $workEnd, int $slotDuration, int $breakMinutes): array
    {
        $step = $slotDuration + $breakMinutes;

        if ($step <= 0) {
            return [];
        }

        $start = Carbon::parse($workStart);
        $end = Carbon::parse($workEnd);

        $times = [];
        $cursor = $start->copy();

        while ($cursor->copy()->addMinutes($slotDuration)->lessThanOrEqualTo($end)) {
            $times[] = $cursor->format('H:i');
            $cursor->addMinutes($step);
        }

        return $times;
    }
}
