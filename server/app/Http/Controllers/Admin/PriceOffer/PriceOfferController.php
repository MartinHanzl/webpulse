<?php

namespace App\Http\Controllers\Admin\PriceOffer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PriceOffer\PriceOfferResource;
use App\Http\Resources\Admin\PriceOffer\PriceOfferSimpleResource;
use App\Models\PriceOffer\PriceOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PriceOfferController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PriceOffer::query();

        if ($request->has('search') && $request->get('search') != '' && $request->get('search') != null) {
            $searchString = $request->get('search');
            if (str_contains(':', $searchString)) {
                $searchString = explode(':', $searchString);
                $query->where($searchString[0], 'like', '%' . $searchString[1] . '%');
            } else {
                $query->where('name', 'like', '%' . $searchString . '%')
                    ->orWhere('color', 'like', '%' . $searchString . '%');
            }
        }

        if ($request->has('orderWay') && $request->get('orderBy')) {
            $query->orderBy($request->get('orderBy'), $request->get('orderWay'));
        }

        if ($request->has('paginate')) {
            $priceOffers = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => PriceOfferSimpleResource::collection($priceOffers->items()),
                'total' => $priceOffers->total(),
                'perPage' => $priceOffers->perPage(),
                'currentPage' => $priceOffers->currentPage(),
                'lastPage' => $priceOffers->lastPage(),
            ]);
        }

        $priceOffers = $query->get();
        return Response::json(PriceOfferSimpleResource::collection($priceOffers));
    }

    public function store(Request $request, int $id = null): JsonResponse
    {
        if ($id) {
            $priceOffer = PriceOffer::find($id);
            if (!$priceOffer) {
                App::abort(404);
            }
        } else {
            $priceOffer = new PriceOffer();
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'tax_rate_id' => 'nullable|integer|exists:tax_rates,id',
            'client_id' => 'nullable|integer|exists:contacts,id',
            'currency_id' => 'nullable|integer|exists:currencies,id',
            'invoice_country_id' => 'nullable|integer|exists:countries,id',
            'delivery_country_id' => 'nullable|integer|exists:countries,id',
            'status_id' => 'nullable|integer|exists:project_statuses,id',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        try {
            DB::beginTransaction();

            $priceOffer->fill($request->all());

            if ($request->has('formatted_start_date') && $request->get('formatted_start_date') != null) {
                $priceOffer->start_date = Carbon::parse($request->get('formatted_start_date'));
            } else {
                $priceOffer->start_date = null;
            }

            if ($request->has('formatted_end_date') && $request->get('formatted_end_date') != null) {
                $priceOffer->end_date = Carbon::parse($request->get('formatted_end_date'));
            } else {
                $priceOffer->end_date = null;
            }

            $priceOffer->save();

            DB::commit();
        } catch (\Throwable|\Exception $e) {
            DB::rollBack();
            return Response::json(['message' => 'An error occurred while updating project.'], 500);
        }

        return Response::json(PriceOfferResource::make($priceOffer));
    }

    public function show(int $id): JsonResponse
    {
        if (!$id) {
            App::abort(400);
        }

        $priceOffer = PriceOffer::find($id);
        if (!$priceOffer) {
            App::abort(404);
        }

        return Response::json(PriceOfferResource::make($priceOffer));
    }

    public function destroy(int $id)
    {
        if (!$id) {
            App::abort(400);
        }

        $priceOffer = PriceOffer::find($id);
        if (!$priceOffer) {
            App::abort(404);
        }

        $priceOffer->delete();
        return Response::json();
    }
}
