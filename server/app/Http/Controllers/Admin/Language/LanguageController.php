<?php

namespace App\Http\Controllers\Admin\Language;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Language\LanguageResource;
use App\Models\Language;
use App\Models\LanguageTranslation;
use App\Services\GoogleTranslatorService;
use Illuminate\Support\Facades\App;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    protected $gTranslator = GoogleTranslatorService::class;

    public function __construct(GoogleTranslatorService $gTranslator)
    {
        $this->gTranslator = $gTranslator;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Language::query();

        if ($request->has('orderBy') && $request->has('orderWay')) {
            $query->orderBy($request->get('orderBy'), $request->get('orderWay'));
        }

        if ($request->has('search') && $request->get('search') !== '') {
            $query->where('code', 'like', '%' . $request->get('search') . '%')
                ->orWhere('iso', 'like', '%' . $request->get('search') . '%')
                ->orWhereRelation('translations', 'name', 'like', '%' . $request->get('search') . '%');
        }

        if ($request->has('page') && $request->has('perPage')) {
            $languages = $query->paginate($request->get('perPage'));
            return Response::json([
                'total' => $languages->total(),
                'perPage' => $languages->perPage(),
                'currentPage' => $languages->currentPage(),
                'lastPage' => $languages->lastPage(),
                'from' => $languages->firstItem(),
                'data' => LanguageResource::collection($languages->items()),
            ]);
        }

        return Response::json(['data' => LanguageResource::collection($query->get())]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'code' => 'required|string|max:2',
            'iso' => 'required|string|max:5',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        $language = new Language();
        $language->fill($data);
        $language->save();

        if ($data['translations'] && !empty($data['translations'])) {
            foreach ($data['translations'] as $locale => $translation) {
                LanguageTranslation::query()
                    ->where('language_id', '=', $language->id)
                    ->where('locale', '=', $locale)
                    ->firstOrNew([
                        'language_id' => $language->id,
                        'locale' => $locale,
                        'name' => $this->gTranslator->translate($data['name'], 'cs', $locale)
                    ])->save();
            }
        }

        return Response::json(['data' => LanguageResource::make($language)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        if (!$id) {
            App::abort(403, 'Id is required');
        }

        $language = Language::query()->find($id);

        if (!$language) {
            App::abort(404, 'Language not found');
        }

        return Response::json(['data' => LanguageResource::make($language)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'code' => 'required|string|max:2',
            'iso' => 'required|string|max:5',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        $language = new Language();
        $language->fill($data);
        $language->save();

        if ($data['translations'] && !empty($data['translations'])) {
            foreach ($data['translations'] as $locale => $translation) {
                LanguageTranslation::query()
                    ->where('language_id', '=', $language->id)
                    ->where('locale', '=', $locale)
                    ->firstOrNew([
                        'language_id' => $language->id,
                        'locale' => $locale,
                        'name' => $this->gTranslator->translate($data['name'], 'cs', $locale)
                    ])->save();
            }
            LanguageTranslation::query()
                ->where('language_id', $language->id)
                ->whereNotIn('locale', array_keys($data['translations']))->delete();
        }

        return Response::json(['data' => LanguageResource::make($language)], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        if (!$id) {
            App::abort(403, 'Id is required');
        }

        $language = Language::query()->find($id);

        if (!$language) {
            App::abort(404, 'Language not found');
        }

        $language->delete();

        return Response::json();
    }
}
