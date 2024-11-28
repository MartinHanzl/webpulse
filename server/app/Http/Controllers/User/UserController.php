<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->has('search') && $request->get('search') != '' && $request->get('search') != null) {
            $searchString = $request->get('search');
            if (str_contains(':', $searchString)) {
                $searchString = explode(':', $searchString);
                $query->where($searchString[0], 'like', '%' . $searchString[1] . '%');
            } else {
                $query->where('firstname', 'like', '%' . $searchString . '%')
                    ->orWhere('lastname', 'like', '%' . $searchString . '%')
                    ->orWhere('email', 'like', '%' . $searchString . '%')
                    ->orWhere('phone', 'like', '%' . $searchString . '%');
            }
        }

        if ($request->has('orderWay') && $request->get('orderBy')) {
            $query->orderBy($request->get('orderBy'), $request->get('orderWay'));
        }

        if ($request->has('paginate')) {
            $users = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => UserResource::collection($users->items()),
                'total' => $users->total(),
                'perPage' => $users->perPage(),
                'currentPage' => $users->currentPage(),
                'lastPage' => $users->lastPage(),
            ]);
        }

        $users = $query->get();
        return Response::json(UserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $id): JsonResponse
    {
        if ($id) {
            $user = User::find($id);
            if (!$user) {
                App::abort(404);
            }
        } else {
            $user = new User();
        }

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:255|unique:users',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $user->fill($request->all());
            $user->save();

            DB::commit();
        } catch (\Throwable|\Exception $e) {
            DB::rollBack();
            return Response::json(['error' => $e->getMessage()], 500);
        }

        return Response::json(UserResource::make($user));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        if (!$id) {
            App::abort(400);
        }

        $user = User::find($id);
        if (!$user) {
            App::abort(404);
        }

        return Response::json(UserResource::make($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if (!$id) {
            App::abort(400);
        }

        $user = User::find($id);
        if (!$user) {
            App::abort(404);
        }

        $user->delete();
        return Response::json();
    }

    public function profile(Request $request): JsonResponse
    {
        $authUser = $request->user();

        $user = User::find($authUser->id);

        if (!$user) {
            App::abort(404);
        }

        return Response::json(UserResource::make($user));
    }
}
