<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Contact\ContactBoardSectionResource;
use App\Models\Contact\ContactBoardCard;
use App\Models\Contact\ContactBoardSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ContactBoardSectionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sections = ContactBoardSection::query()
            ->where('user_id', $request->user()->id)
            ->withCount('cards')
            ->with(['cards.contact'])
            ->orderBy('position')
            ->get();

        return Response::json(ContactBoardSectionResource::collection($sections));
    }

    public function store(Request $request, ?int $id = null): JsonResponse
    {
        if ($id) {
            $section = ContactBoardSection::where('user_id', $request->user()->id)->find($id);
            if (! $section) {
                App::abort(404);
            }
        } else {
            $section = new ContactBoardSection;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $section->fill($request->only(['name', 'color']));
            $section->user_id = $request->user()->id;

            if (! $section->exists) {
                $section->position = (int) ContactBoardSection::where('user_id', $request->user()->id)->max('position') + 1;
            }

            $section->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['message' => 'Chyba při ukládání sekce.'], 500);
        }

        return Response::json(ContactBoardSectionResource::make($section->fresh()));
    }

    public function reorder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($request->get('ids') as $position => $sectionId) {
                ContactBoardSection::where('user_id', $request->user()->id)
                    ->where('id', $sectionId)
                    ->update(['position' => $position]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['message' => 'Chyba při přeuspořádání sekcí.'], 500);
        }

        return Response::json(['status' => 'ok']);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $section = ContactBoardSection::where('user_id', $request->user()->id)->find($id);
        if (! $section) {
            App::abort(404);
        }

        $moveToSectionId = $request->get('move_to_section_id');
        $targetSection = null;

        if ($moveToSectionId) {
            $targetSection = ContactBoardSection::where('user_id', $request->user()->id)
                ->where('id', $moveToSectionId)
                ->first();

            if (! $targetSection || $targetSection->id === $section->id) {
                return Response::json(['message' => 'Cílová sekce je neplatná.'], 422);
            }
        }

        DB::beginTransaction();
        try {
            $cards = ContactBoardCard::where('contact_board_section_id', $section->id)
                ->orderBy('position')
                ->lockForUpdate()
                ->get();

            if ($cards->isNotEmpty() && ! $targetSection) {
                DB::rollBack();

                return Response::json([
                    'message' => 'Sekce obsahuje karty. Vyberte sekci, do které se mají přesunout.',
                    'cards_count' => $cards->count(),
                ], 422);
            }

            if ($cards->isNotEmpty()) {
                $nextPosition = (int) ContactBoardCard::where('contact_board_section_id', $targetSection->id)->max('position') + 1;

                foreach ($cards as $card) {
                    $card->update([
                        'contact_board_section_id' => $targetSection->id,
                        'position' => $nextPosition++,
                    ]);
                }
            }

            $section->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['message' => 'Chyba při mazání sekce.'], 500);
        }

        return Response::json();
    }
}
