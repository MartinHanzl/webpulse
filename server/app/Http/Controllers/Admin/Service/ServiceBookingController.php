<?php

namespace App\Http\Controllers\Admin\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Service\ServiceBookingResource;
use App\Models\Service\Service;
use App\Models\Service\ServiceBooking;
use App\Services\Booking\SlotGenerator;
use App\Traits\Siteable;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ServiceBookingController extends Controller
{
    use Siteable;

    public function index(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $query = ServiceBooking::with('service')
            ->whereRelation('sites', 'site_id', $siteId);

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->get('service_id'));
        }

        if ($request->filled('date')) {
            $query->where('date', $request->get('date'));
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->get('date_to'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($request->has('orderWay') && $request->get('orderBy')) {
            $query->orderBy($request->get('orderBy'), $request->get('orderWay'));
        } else {
            $query->orderBy('date', 'desc')->orderBy('time_from', 'desc');
        }

        if ($request->has('paginate')) {
            $bookings = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => ServiceBookingResource::collection($bookings->items()),
                'total' => $bookings->total(),
                'perPage' => $bookings->perPage(),
                'currentPage' => $bookings->currentPage(),
                'lastPage' => $bookings->lastPage(),
            ]);
        }

        return Response::json(ServiceBookingResource::collection($query->get()));
    }

    public function slots(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer|exists:services,id',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        $service = Service::query()
            ->whereRelation('sites', 'site_id', $siteId)
            ->find($request->get('service_id'));

        if (! $service) {
            App::abort(404);
        }

        $slots = (new SlotGenerator)->forServiceAndDate($service, Carbon::parse($request->get('date')));

        return Response::json($slots);
    }

    public function store(Request $request, ?int $id = null): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        if ($id) {
            $booking = ServiceBooking::whereRelation('sites', 'site_id', $siteId)->find($id);
            if (! $booking) {
                App::abort(404);
            }
        } else {
            $booking = new ServiceBooking;
        }

        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer|exists:services,id',
            'date' => 'required|date',
            'time_from' => 'required',
            'time_to' => 'nullable',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'note' => 'nullable|string|max:2000',
            'status' => 'nullable|in:pending,confirmed,cancelled,completed,no_show',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        $service = Service::query()
            ->whereRelation('sites', 'site_id', $siteId)
            ->find($request->get('service_id'));
        if (! $service) {
            return Response::json(['message' => 'Služba nepatří k tomuto webu.'], 404);
        }

        // Check for time conflict
        $conflictQuery = ServiceBooking::where('service_id', $request->get('service_id'))
            ->where('date', $request->get('date'))
            ->where('time_from', $request->get('time_from'))
            ->whereNotIn('status', ['cancelled', 'no_show']);

        if ($id) {
            $conflictQuery->where('id', '!=', $id);
        }

        if ($conflictQuery->exists()) {
            return Response::json(['message' => 'Na tento termín již existuje rezervace.'], 409);
        }

        try {
            DB::beginTransaction();
            $booking->fill($request->all());
            $booking->save();
            $this->saveSites($booking, [$siteId]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ServiceBooking save error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return Response::json(['message' => 'Chyba při ukládání rezervace: '.$e->getMessage()], 500);
        }

        return Response::json(ServiceBookingResource::make($booking->fresh('service')));
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $booking = ServiceBooking::whereRelation('sites', 'site_id', $siteId)->find($id);
        if (! $booking) {
            App::abort(404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,cancelled,completed,no_show',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        $booking->status = $request->get('status');
        $booking->save();

        return Response::json(ServiceBookingResource::make($booking->fresh('service')));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $booking = ServiceBooking::whereRelation('sites', 'site_id', $siteId)->find($id);
        if (! $booking) {
            App::abort(404);
        }

        $booking->delete();

        return Response::json();
    }
}
