<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\LanguageResource;
use App\Http\Resources\Admin\Settings\LinkResource;
use App\Http\Resources\Me\MeResource;
use App\Models\Language;
use App\Models\LanguageTranslation;
use App\Models\Settings\Link;
use App\Models\Settings\LinkTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    public function list(Request $request, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);
        $languages = Language::query()->get();

        return Response::json(
            LanguageResource::collection($languages)
        );
    }

    public function detail(Request $request, int $id, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);

        if (!$id) {
            App::abort(400);
        }

        $language = Language::query()
            ->find($id);

        if (!$language) {
            App::abort(404);
        }

        return Response::json(
            LanguageResource::make($language)
        );
    }

    public function create(Request $request, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);

        $data = $request->all();

        $validator = Validator::make($data, [
            'default_locale' => 'required|min:2',
            'default_name' => 'required',
            'translations' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json($validator->messages());
        }

        $language = new Language();
        $language->fill($data);
        $language->save();
        /*$language = DB::table('languages')->insert([
            'locale' => $data['locale'],
            'name' => $data['name'],
            'active' => $data['active'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $language = Language::query()
            ->orderBy('id', 'DESC')->first();*/

        foreach ($data['translations'] as $key => $translation) {
            $languageTranslation = new LanguageTranslation();
            $languageTranslation->fill([
                'locale' => $key,
                'language_id' => $language->id,
                'name' => $translation['name']
            ]);
            $languageTranslation->save();
        }
        return Response::json(
            LanguageResource::make($language)
        );
    }

    public function edit(Request $request, int $id, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);

        if (!$id) {
            App::abort(400);
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'translations' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json($validator->messages());
        }

        $language = Language::query()->find($id);

        if (!$language) {
            App::abort(404);
        }

        $language->fill($data);
        $language->save();

        /*foreach ($data['translations'] as $key => $translation) {
            $languageTranslation = LanguageTranslation::query()->firstOrNew([
                'id' =>
            ])
        }*/

        return Response::json();
    }

    public function delete(Request $request, int $id, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);
        if (!$id) {
            App::abort(400);
        }

        $language = Language::query()->find($id);

        if (!$language) {
            App::abort(404);
        }

        $language->delete();
        return Response::json();
    }
}
