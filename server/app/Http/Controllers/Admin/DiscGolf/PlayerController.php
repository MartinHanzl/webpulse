<?php

namespace App\Http\Controllers\Admin\DiscGolf;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\DiscGolf\PlayerResource;
use App\Models\DiscGolf\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $query = Player::query()
            ->withCount('gamePlayers')
            ->whereRelation('sites', 'site_id', $siteId);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->get('search').'%');
        }

        if ($request->has('orderBy') && $request->has('orderWay')) {
            $query->orderBy($request->get('orderBy'), $request->get('orderWay'));
        } else {
            $query->orderBy('position');
        }

        if ($request->has('paginate')) {
            $items = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => PlayerResource::collection($items->items()),
                'total' => $items->total(),
                'perPage' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'lastPage' => $items->lastPage(),
            ]);
        }

        return Response::json(PlayerResource::collection($query->get()));
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $item = Player::whereRelation('sites', 'site_id', $siteId)->find($id);

        if (! $item) {
            App::abort(404);
        }

        return Response::json(PlayerResource::make($item));
    }

    public function store(Request $request, ?int $id = null)
    {
        if ($id) {
            $item = Player::find($id);
            if (! $item) {
                App::abort(404);
            }
        } else {
            $item = new Player;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        DB::beginTransaction();
        try {
            $item->fill($request->all());
            $item->save();

            $item->saveSites($item, $request->get('sites', []));

            DB::commit();

            return Response::json(PlayerResource::make($item->fresh()));
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['error' => 'An error occurred while saving the player.'], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $item = Player::find($id);
        if (! $item) {
            App::abort(404);
        }

        $item->delete();

        return Response::json();
    }

    public function reorder(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $allowedIds = Player::whereRelation('sites', 'site_id', $siteId)->pluck('id')->toArray();

        DB::transaction(function () use ($request, $allowedIds) {
            foreach ($request->get('order', []) as $entry) {
                if (! in_array((int) $entry['id'], $allowedIds, true)) {
                    continue;
                }
                Player::where('id', $entry['id'])->update(['position' => $entry['position']]);
            }
        });

        return Response::json();
    }
}
