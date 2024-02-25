<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Settings\LinkResource;
use App\Http\Resources\Client\LanguageResource;
use App\Http\Resources\Me\MeResource;
use App\Models\Language;
use App\Models\Settings\Link;
use App\Models\Settings\LinkTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    public function list(Request $request, string $lang = null): JsonResponse
    {
        $this->handleLang($lang);

        $languages = Language::query()
            ->where('active', '=', true)
            ->where('default_locale', '!=', $lang)
            ->get();

        return Response::json(
            LanguageResource::collection($languages)
        );
    }
}
