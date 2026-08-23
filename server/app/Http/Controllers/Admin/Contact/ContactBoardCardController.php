<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Contact\ContactBoardCardResource;
use App\Models\Contact\Contact;
use App\Models\Contact\ContactBoardCard;
use App\Models\Contact\ContactBoardSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ContactBoardCardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ContactBoardCard::query()
            ->where('user_id', $request->user()->id)
            ->with(['contact']);

        $orderBy = $request->get('orderBy', 'position');
        $orderWay = $request->get('orderWay', 'asc');

        if ($orderBy === 'due_date') {
            $query->whereNotNull('due_date');
        }

        $query->orderBy($orderBy, $orderWay);

        if ($request->has('paginate')) {
            $cards = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => ContactBoardCardResource::collection($cards->items()),
                'total' => $cards->total(),
                'perPage' => $cards->perPage(),
                'currentPage' => $cards->currentPage(),
                'lastPage' => $cards->lastPage(),
            ]);
        }

        return Response::json(ContactBoardCardResource::collection($query->get()));
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $card = ContactBoardCard::where('user_id', $request->user()->id)
            ->with(['contact'])
            ->find($id);

        if (! $card) {
            App::abort(404);
        }

        return Response::json(ContactBoardCardResource::make($card));
    }

    public function store(Request $request, ?int $id = null): JsonResponse
    {
        if ($id) {
            $card = ContactBoardCard::where('user_id', $request->user()->id)->find($id);
            if (! $card) {
                App::abort(404);
            }
        } else {
            $card = new ContactBoardCard;
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'contact_board_section_id' => 'required|integer|exists:contact_board_sections,id',
            'contact_id' => 'required|integer|exists:contacts,id',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high,critical',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        $section = ContactBoardSection::where('user_id', $request->user()->id)
            ->where('id', $request->get('contact_board_section_id'))
            ->first();

        if (! $section) {
            return Response::json(['message' => 'Sekce nenalezena.'], 422);
        }

        $contact = Contact::where('user_id', $request->user()->id)
            ->where('id', $request->get('contact_id'))
            ->first();

        if (! $contact) {
            return Response::json(['message' => 'Kontakt nenalezen.'], 422);
        }

        try {
            DB::beginTransaction();

            $sectionChanged = $card->exists && (int) $card->contact_board_section_id !== $section->id;

            $card->fill($request->only([
                'title', 'contact_board_section_id', 'contact_id',
                'description', 'note', 'priority', 'due_date',
            ]));
            $card->user_id = $request->user()->id;

            if (! $card->exists || $sectionChanged) {
                $card->position = (int) ContactBoardCard::where('contact_board_section_id', $card->contact_board_section_id)->max('position') + 1;
            }

            $card->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['message' => 'Chyba při ukládání karty.'], 500);
        }

        return Response::json(ContactBoardCardResource::make($card->fresh(['contact'])));
    }

    public function reorder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.position' => 'required|integer',
            'items.*.contact_board_section_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        $sectionIds = collect($request->get('items'))->pluck('contact_board_section_id')->unique();
        $ownedSectionsCount = ContactBoardSection::where('user_id', $request->user()->id)
            ->whereIn('id', $sectionIds)
            ->count();

        if ($ownedSectionsCount !== $sectionIds->count()) {
            return Response::json(['message' => 'Neplatná cílová sekce.'], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($request->get('items') as $item) {
                ContactBoardCard::where('user_id', $request->user()->id)
                    ->where('id', $item['id'])
                    ->update([
                        'position' => $item['position'],
                        'contact_board_section_id' => $item['contact_board_section_id'],
                    ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['message' => 'Chyba při přeuspořádání karet.'], 500);
        }

        return Response::json(['status' => 'ok']);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $card = ContactBoardCard::where('user_id', $request->user()->id)->find($id);
        if (! $card) {
            App::abort(404);
        }

        $card->delete();

        return Response::json();
    }
}
