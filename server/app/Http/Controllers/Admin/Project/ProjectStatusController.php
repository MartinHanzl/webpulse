<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Project\ProjectStatusResource;
use App\Models\Project\ProjectStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ProjectStatusController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ProjectStatus::query();

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
            $projectStatuses = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => ProjectStatusResource::collection($projectStatuses->items()),
                'total' => $projectStatuses->total(),
                'perPage' => $projectStatuses->perPage(),
                'currentPage' => $projectStatuses->currentPage(),
                'lastPage' => $projectStatuses->lastPage(),
            ]);
        }

        $projectStatuses = $query->get();
        return Response::json(ProjectStatusResource::collection($projectStatuses));
    }

    public function store(Request $request, int $id = null): JsonResponse
    {
        if ($id) {
            $projectStatus = ProjectStatus::find($id);
            if (!$projectStatus) {
                App::abort(404);
            }
        } else {
            $projectStatus = new ProjectStatus();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'color' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        try {
            DB::beginTransaction();

            $projectStatus->fill($request->all());
            $projectStatus->save();

            DB::commit();
        } catch (\Throwable|\Exception $e) {
            DB::rollBack();
            return Response::json(['message' => 'An error occurred while updating tax rate.'], 500);
        }

        return Response::json(ProjectStatusResource::make($projectStatus));
    }

    public function show(int $id): JsonResponse
    {
        if (!$id) {
            App::abort(400);
        }

        $projectStatus = ProjectStatus::find($id);
        if (!$projectStatus) {
            App::abort(404);
        }

        return Response::json(ProjectStatusResource::make($projectStatus));
    }

    public function destroy(int $id)
    {
        if (!$id) {
            App::abort(400);
        }

        $projectStatus = ProjectStatus::find($id);
        if (!$projectStatus) {
            App::abort(404);
        }

        $projectStatus->delete();
        return Response::json();
    }
}
