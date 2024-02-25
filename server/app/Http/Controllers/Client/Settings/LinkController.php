<?php

namespace App\Http\Controllers\Client\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Settings\LinkResource;
use App\Http\Resources\Me\MeResource;
use App\Models\Settings\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class LinkController extends Controller
{
    public function list(Request $request, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);

        $query = Link::query()
            ->where('type', '=', 'client')
            ->where('active', '=', true);

        $links = $query->get();

        return Response::json(
            LinkResource::collection($links)
        );
    }
}
