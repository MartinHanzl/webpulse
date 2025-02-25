<?php

namespace App\Http\Controllers\Admin\Activity;

use App\Http\Controllers\Controller;
use App\Http\Resources\Activity\ActivityResource;
use App\Models\Activity\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Activity::query();

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
        } else {
            $query->orderBy('name', 'asc');
        }

        if ($request->has('paginate')) {
            $activities = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => ActivityResource::collection($activities->items()),
                'total' => $activities->total(),
                'perPage' => $activities->perPage(),
                'currentPage' => $activities->currentPage(),
                'lastPage' => $activities->lastPage(),
            ]);
        }

        $activities = $query->get();
        return Response::json(ActivityResource::collection($activities));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $id = null): JsonResponse
    {
        if ($id) {
            $activity = Activity::find($id);

            if (!$activity) {
                App::abort(404);
            }
        } else {
            $activity = new Activity();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $activity->fill($request->all());
            $activity->save();

            DB::commit();
        } catch (\Throwable|\Exception $e) {
            DB::rollBack();
            return Response::json(['message' => 'An error occurred while updating activity.'], 500);
        }

        return Response::json(ActivityResource::make($activity));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        if (!$id) {
            App::abort(400);
        }

        $activity = Activity::find($id);

        if (!$activity) {
            App::abort(404);
        }

        return Response::json(ActivityResource::make($activity));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        if (!$id) {
            App::abort(400);
        }

        $activity = Activity::find($id);

        if (!$activity) {
            App::abort(404);
        }
        $activity->delete();

        return Response::json([]);
    }
}
