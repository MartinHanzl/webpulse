<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contact\ContactPhaseResource;
use App\Models\Contact\ContactPhase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ContactPhaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ContactPhase::query()
            ->with(['tasks'])
            ->where('user_id', $request->user()->id);

        if ($request->has('search') && $request->get('search') != '' && $request->get('search') != null) {
            $searchString = $request->get('search');
            if (str_contains(':', $searchString)) {
                $searchString = explode(':', $searchString);
                $query->where($searchString[0], 'like', '%' . $searchString[1] . '%');
            } else {
                $query->where('name', 'like', '%' . $searchString . '%');
            }
        }

        if ($request->has('orderWay') && $request->get('orderBy')) {
            $query->orderBy($request->get('orderBy'), $request->get('orderWay'));
        }

        if ($request->has('paginate')) {
            $contactPhases = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => ContactPhaseResource::collection($contactPhases->items()),
                'total' => $contactPhases->total(),
                'perPage' => $contactPhases->perPage(),
                'currentPage' => $contactPhases->currentPage(),
                'lastPage' => $contactPhases->lastPage(),
            ]);
        }

        $contactPhases = $query->get();
        return Response::json(ContactPhaseResource::collection($contactPhases));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $id = null): JsonResponse
    {
        if ($id) {
            $contactPhase = ContactPhase::find($id);
            if (!$contactPhase) {
                App::abort(404);
            }
        } else {
            $contactPhase = new ContactPhase();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $contactPhase->fill($request->all());
            $contactPhase->user_id = $request->user()->id;
            $contactPhase->save();

            DB::commit();
        } catch (\Throwable | \Exception $e) {
            DB::rollBack();
            return Response::json(['message' => 'An error occurred while updating contact phase.'], 500);
        }

        return Response::json(ContactPhaseResource::make($contactPhase));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        if (!$id) {
            App::abort(400);
        }

        $contactPhase = ContactPhase::find($id);
        if (!$contactPhase) {
            App::abort(404);
        }

        return Response::json(ContactPhaseResource::make($contactPhase));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if (!$id) {
            App::abort(400);
        }

        $contactPhase = ContactPhase::find($id);
        if (!$contactPhase) {
            App::abort(404);
        }

        $contactPhase->delete();
        return Response::json();
    }
}
