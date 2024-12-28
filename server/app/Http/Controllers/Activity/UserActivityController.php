<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Http\Resources\Activity\UserActivityResource;
use App\Models\Activity\UserActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

        if ($request->has('month') && $request->has('year')) {
            $query->whereMonth('date', $request->get('month'))
                ->whereYear('date', $request->get('year'));
        } else {
            $query->whereMonth('date', date('n'))
                ->whereYear('date', date('Y'));
        }

        return Response::json(UserActivityResource::collection($query->get()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $id = null): JsonResponse
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
            'activity_id' => 'required|integer|exists:activities,id',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $userActivity->fill($request->all());

            $userActivity->user_id = $request->user()->id;
            $userActivity->activity_id = $request->get('activity_id');

            if ($request->has('formatted_date') && $request->get('formatted_date') != null) {
                $userActivity->date = Carbon::parse($request->get('formatted_date'));
            } else {
                $userActivity->date = Carbon::now();
            }

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
