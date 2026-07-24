<?php

namespace App\Http\Controllers\Admin\Food\CalorieMeasurement;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Food\CalorieMeasurement\CalorieMeasurementResource;
use App\Models\Food\CalorieMeasurement\CalorieMeasurement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CalorieMeasurementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $query = CalorieMeasurement::query()
            ->whereRelation('sites', 'site_id', $siteId);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->get('search').'%');
        }

        if ($request->has('orderWay') && $request->get('orderBy')) {
            $query->orderBy($request->get('orderBy'), $request->get('orderWay'));
        } else {
            $query->orderBy('updated_at', 'desc');
        }

        if ($request->has('paginate')) {
            $measurements = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => CalorieMeasurementResource::collection($measurements->items()),
                'total' => $measurements->total(),
                'perPage' => $measurements->perPage(),
                'currentPage' => $measurements->currentPage(),
                'lastPage' => $measurements->lastPage(),
            ]);
        }

        return Response::json(CalorieMeasurementResource::collection($query->get()));
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $measurement = CalorieMeasurement::query()
            ->whereRelation('sites', 'site_id', $siteId)
            ->find($id);

        if (! $measurement) {
            App::abort(404);
        }

        return Response::json(CalorieMeasurementResource::make($measurement));
    }

    public function store(Request $request, ?int $id = null): JsonResponse
    {
        if ($id) {
            $measurement = CalorieMeasurement::find($id);
            if (! $measurement) {
                App::abort(404);
            }
        } else {
            $measurement = new CalorieMeasurement;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'portions' => 'nullable|integer|min:1',
            'items' => 'nullable|array',
            'items.*.foodstuff_id' => 'nullable|integer',
            'items.*.name' => 'required|string|max:255',
            'items.*.amount' => 'nullable|numeric|min:0',
            'sites' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        DB::beginTransaction();
        try {
            $measurement->fill([
                'name' => $request->get('name'),
                'portions' => max(1, (int) $request->get('portions', 1)),
                'items' => $request->get('items', []),
            ]);
            $measurement->save();

            $measurement->saveSites($measurement, $request->get('sites', []));

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['message' => 'An error occurred while saving the measurement.'], 500);
        }

        return Response::json(CalorieMeasurementResource::make($measurement));
    }

    public function destroy(int $id): JsonResponse
    {
        $measurement = CalorieMeasurement::find($id);
        if (! $measurement) {
            App::abort(404);
        }

        $measurement->delete();

        return Response::json();
    }
}
