<?php

namespace App\Http\Controllers\Client\Me;

use App\Http\Controllers\Controller;
use App\Http\Resources\Me\MeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class MeController extends Controller
{
    public function profile(Request $request): JsonResponse
    {
        return Response::json(MeResource::make(Auth::user()));
    }
}
