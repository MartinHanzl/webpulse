<?php

namespace App\Http\Controllers\Admin\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Service\ServiceBookingSettingResource;
use App\Models\Service\Service;
use App\Models\Service\ServiceBookingSetting;
use App\Models\Service\ServiceBookingSettingException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ServiceBookingSettingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $services = Service::query()
            ->whereRelation('sites', 'site_id', $siteId)
            ->with('bookingSetting')
            ->get();

        $data = $services->map(function (Service $service) {
            return [
                'service_id' => $service->id,
                'service_name' => $service->name,
                'active' => (bool) $service->active,
                'booking_setting' => $service->bookingSetting
                    ? ServiceBookingSettingResource::make($service->bookingSetting)
                    : null,
            ];
        });

        return Response::json($data);
    }

    public function show(Request $request, int $serviceId): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $service = Service::query()
            ->whereRelation('sites', 'site_id', $siteId)
            ->find($serviceId);

        if (! $service) {
            App::abort(404);
        }

        $setting = ServiceBookingSetting::query()
            ->where('service_id', $serviceId)
            ->with('exceptions')
            ->first();

        return Response::json([
            'service_id' => $service->id,
            'service_name' => $service->name,
            'booking_setting' => $setting ? ServiceBookingSettingResource::make($setting) : null,
        ]);
    }

    public function store(Request $request, int $serviceId): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $service = Service::query()
            ->whereRelation('sites', 'site_id', $siteId)
            ->find($serviceId);

        if (! $service) {
            App::abort(404);
        }

        $validator = Validator::make($request->all(), [
            'work_start' => 'required|date_format:H:i',
            'work_end' => 'required|date_format:H:i|after:work_start',
            'slot_duration_minutes' => 'required|integer|min:1',
            'break_minutes' => 'nullable|integer|min:0',
            'working_days' => 'nullable|array',
            'working_days.*' => 'integer|min:1|max:7',
            'active' => 'nullable|boolean',
            'exceptions' => 'nullable|array',
            'exceptions.*.date' => 'required_with:exceptions|date',
            'exceptions.*.is_closed' => 'nullable|boolean',
            'exceptions.*.custom_times' => 'nullable|array',
            'exceptions.*.custom_times.*' => 'date_format:H:i',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        $setting = ServiceBookingSetting::query()->where('service_id', $serviceId)->first();
        if (! $setting) {
            $setting = new ServiceBookingSetting;
            $setting->service_id = $serviceId;
        }

        try {
            DB::beginTransaction();

            $setting->fill($request->only([
                'work_start',
                'work_end',
                'slot_duration_minutes',
                'break_minutes',
                'working_days',
                'active',
            ]));
            $setting->save();

            $existingExceptionIds = $setting->exceptions()->pluck('id')->all();
            $keptExceptionIds = [];

            foreach ($request->get('exceptions', []) as $exceptionData) {
                $exception = ServiceBookingSettingException::query()
                    ->where('booking_setting_id', $setting->id)
                    ->where('date', $exceptionData['date'])
                    ->first();

                if (! $exception) {
                    $exception = new ServiceBookingSettingException;
                    $exception->booking_setting_id = $setting->id;
                }

                $exception->date = $exceptionData['date'];
                $exception->is_closed = $exceptionData['is_closed'] ?? false;
                $exception->custom_times = $exceptionData['custom_times'] ?? null;
                $exception->save();

                $keptExceptionIds[] = $exception->id;
            }

            $toDelete = array_diff($existingExceptionIds, $keptExceptionIds);
            if (! empty($toDelete)) {
                ServiceBookingSettingException::query()->whereIn('id', $toDelete)->delete();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ServiceBookingSetting save error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return Response::json(['message' => 'Chyba při ukládání nastavení rezervací: '.$e->getMessage()], 500);
        }

        return Response::json(ServiceBookingSettingResource::make($setting->fresh('exceptions')));
    }

    public function destroy(Request $request, int $serviceId): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $service = Service::query()
            ->whereRelation('sites', 'site_id', $siteId)
            ->find($serviceId);

        if (! $service) {
            App::abort(404);
        }

        $setting = ServiceBookingSetting::query()->where('service_id', $serviceId)->first();
        if (! $setting) {
            App::abort(404);
        }

        $setting->delete();

        return Response::json();
    }
}
