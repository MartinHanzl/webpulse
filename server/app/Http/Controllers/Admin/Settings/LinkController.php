<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Settings\LinkResource;
use App\Http\Resources\Me\MeResource;
use App\Models\Settings\Link;
use App\Models\Settings\LinkTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class LinkController extends Controller
{
    public function list(Request $request, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);

        $query = Link::query();

        if ($request->has('active')) {
            $query->where('active', '=', true);
        }

        $links = $query->get();

        return Response::json(
            LinkResource::collection($links)
        );
    }

    public function detail(Request $request, int $id, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);

        if (!$id) {
            return Response::json([], 400);
        }

        $link = Link::query()->find($id);
        if (!$link) {
            App::abort(404);
        }


        return Response::json(
            LinkResource::make($link)
        );
    }

    public function create(Request $request, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);

        $data = $request->all();

        $validator = Validator::make($data, [
            'translations' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->messages(), 400);
        }

        $link = new Link();
        $link->fill($data);
        $link->save();

        foreach ($request->get('translations') as $key => $translation) {
            $translation['link_id'] = (int)$link->id;
            $translation['locale'] = (string)$key;
            $linkTranslation = new LinkTranslation();
            $linkTranslation->fill($translation);
            $linkTranslation->save();
        }

        return Response::json(
            LinkResource::make($link)
        );
    }

    public function edit(Request $request, int $id, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);

        if (!$id) {
            return Response::json([], 400);
        }
        return Response::json();
    }

    public function delete(Request $request, int $id, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);

        if (!$id) {
            return Response::json([], 400);
        }
        return Response::json();
    }
}
