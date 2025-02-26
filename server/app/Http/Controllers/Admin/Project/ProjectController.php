<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Project\ProjectResource;
use App\Http\Resources\Admin\Project\ProjectSimpleResource;
use App\Models\Project\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Project::query();

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
            $projects = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => ProjectSimpleResource::collection($projects->items()),
                'total' => $projects->total(),
                'perPage' => $projects->perPage(),
                'currentPage' => $projects->currentPage(),
                'lastPage' => $projects->lastPage(),
            ]);
        }

        $projects = $query->get();
        return Response::json(ProjectSimpleResource::collection($projects));
    }

    public function store(Request $request, int $id = null): JsonResponse
    {
        if ($id) {
            $project = Project::find($id);
            if (!$project) {
                App::abort(404);
            }
        } else {
            $project = new Project();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|text',
            'expected_price' => 'required|decimal',
            'tax_rate_id' => 'nullable|integer|exists:tax_rates,id',
            'client_id' => 'nullable|integer|exists:contacts,id',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        try {
            DB::beginTransaction();

            $project->fill($request->all());
            $project->save();

            DB::commit();
        } catch (\Throwable|\Exception $e) {
            DB::rollBack();
            return Response::json(['message' => 'An error occurred while updating tax rate.'], 500);
        }

        return Response::json(ProjectResource::make($project));
    }

    public function show(int $id): JsonResponse
    {
        if (!$id) {
            App::abort(400);
        }

        $project = Project::find($id);
        if (!$project) {
            App::abort(404);
        }

        return Response::json(ProjectResource::make($project));
    }

    public function destroy(int $id)
    {
        if (!$id) {
            App::abort(400);
        }

        $project = Project::find($id);
        if (!$project) {
            App::abort(404);
        }

        $project->delete();
        return Response::json();
    }
}
