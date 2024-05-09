<?php

namespace App\Http\Controllers\Admin\Me;

use App\Http\Controllers\Controller;
use App\Http\Resources\Me\MeResource;
use App\Models\Administrator\Administrator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class MeController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        dd($request->user()->id);
        $administrator = Administrator::query()->find($request->user()->id);
        return Response::json(MeResource::make(Auth::user()));
    }

    public function profile(Request $request): JsonResponse
    {
        return Response::json(MeResource::make(Auth::user()));
    }
}
