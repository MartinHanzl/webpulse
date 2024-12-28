<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Http\Resources\Activity\UserActivityResource;
use App\Models\Activity\UserActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use function Symfony\Component\String\b;

class UserActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = UserActivity::query()
            ->where('user_id', $request->user()->id);

        if ($request->has('month')) {
            $query->whereMonth('created_at', $request->get('month'));
        } else {
            $query->whereMonth('created_at', date('n'));
        }

        return Response::json(UserActivityResource::collection($query->get()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $id): JsonResponse
    {
        if ($id) {
            $userActivity = UserActivity::query()
                ->where('user_id', $request->user()->id)
                ->find($id);
            if (!$userActivity) {
                App::abort(404);
            }
        } else {
            $userActivity = new UserActivity();
        }

        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $userActivity->user_id = $request->user()->id;
            $userActivity->activity_id = $request->get('activity_id');

            $userActivity->save();

            DB::commit();
        } catch (\Throwable|\Exception $e) {
            DB::rollBack();
            return Response::json(['errors' => $e->getMessage()], 500);
        }

        return Response::json(UserActivityResource::make($userActivity));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        if (!$id) {
            App::abort(400);
        }

        $userActivity = UserActivity::query()
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$userActivity) {
            App::abort(404);
        }

        return Response::json(UserActivityResource::make($userActivity));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$id) {
            App::abort(400);
        }

        $userActivity = UserActivity::query()
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$userActivity) {
            App::abort(404);
        }
        $userActivity->delete();

        return Response::json();
    }
}
