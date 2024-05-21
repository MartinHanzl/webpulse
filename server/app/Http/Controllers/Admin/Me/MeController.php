<?php

namespace App\Http\Controllers\Admin\Me;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Me\MeResource;
use App\Models\Administrator\Administrator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class MeController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $administrator = Administrator::query()->find($request->user()->id);

        if (!$administrator) {
            return Response::json([
                'success' => false,
                'message' => 'Administrator not found'
            ], 404);
        }

        return Response::json(MeResource::make($administrator));
    }

    public function profile(Request $request): JsonResponse
    {
        return Response::json(MeResource::make(Auth::user()));
    }
}
