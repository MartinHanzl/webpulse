<?php

namespace App\Http\Controllers\Client\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Service\ServiceBookingResource;
use App\Models\Service\Service;
use App\Models\Service\ServiceBooking;
use App\Services\Booking\SlotGenerator;
use App\Traits\Siteable;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ServiceBookingController extends Controller
{
    use Siteable;

    public function slots(Request $request, int $id): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        $service = Service::query()
            ->whereRelation('sites', 'site_id', $siteId)
            ->find($id);

        if (! $service) {
            App::abort(404);
        }

        $slots = (new SlotGenerator)->forServiceAndDate($service, Carbon::parse($request->get('date')));

        return Response::json($slots);
    }

    public function store(Request $request, int $id): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
            'time_from' => 'required|date_format:H:i',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'note' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        $service = Service::query()
            ->whereRelation('sites', 'site_id', $siteId)
            ->find($id);

        if (! $service) {
            return Response::json(['error' => 'Service not available on this site.'], 404);
        }

        $availableSlots = (new SlotGenerator)->forServiceAndDate($service, Carbon::parse($request->get('date')));

        if (! in_array($request->get('time_from'), $availableSlots, true)) {
            return Response::json(['error' => 'Zvolený termín již není volný.'], 409);
        }

        DB::beginTransaction();
        try {
            $booking = new ServiceBooking;
            $booking->fill($request->only([
                'service_id',
                'date',
                'time_from',
                'first_name',
                'last_name',
                'phone',
                'email',
                'note',
            ]));
            $booking->service_id = $service->id;
            $booking->status = 'pending';
            $booking->source = 'web';
            $booking->save();
            $this->saveSites($booking, [$siteId]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['error' => 'An error occurred while creating the booking.'], 500);
        }

        return Response::json(ServiceBookingResource::make($booking), 201);
    }
}
