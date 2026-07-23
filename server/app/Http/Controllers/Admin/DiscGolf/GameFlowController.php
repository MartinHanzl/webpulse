<?php

namespace App\Http\Controllers\Admin\DiscGolf;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\DiscGolf\GameResource;
use App\Models\DiscGolf\CourseLayout;
use App\Models\DiscGolf\Game;
use App\Models\DiscGolf\GameHole;
use App\Models\DiscGolf\GameHoleScore;
use App\Models\DiscGolf\GamePlayer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class GameFlowController extends Controller
{
    /**
     * Step 1 — create the game (draft) and materialize its holes from a layout or custom input.
     */
    public function create(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $validator = Validator::make($request->all(), [
            'course_id' => 'nullable|exists:courses,id',
            'course_layout_id' => 'nullable|exists:course_layouts,id',
            'custom_course_name' => 'nullable|string|max:255',
            'custom_layout_name' => 'nullable|string|max:255',
            'played_at' => 'nullable|date',
            'holes' => 'nullable|array',
            'holes.*.par' => 'nullable|integer|min:1',
            'holes.*.meters' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        DB::beginTransaction();
        try {
            $game = new Game;
            $game->course_id = $request->get('course_id');
            $game->course_layout_id = $request->get('course_layout_id');
            $game->custom_course_name = $request->get('custom_course_name');
            $game->custom_layout_name = $request->get('custom_layout_name');
            $game->played_at = $request->get('played_at') ?: now();
            $game->note = $request->get('note');
            $game->status = 'draft';
            $game->save();

            $game->saveSites($game, $request->get('sites', []));

            // Build holes: from the chosen layout, or from custom holes payload.
            $holes = $this->resolveHoles($request);

            $par = 0;
            foreach ($holes as $index => $holeData) {
                GameHole::create([
                    'game_id' => $game->id,
                    'number' => $holeData['number'] ?? ($index + 1),
                    'par' => (int) ($holeData['par'] ?? 3),
                    'meters' => isset($holeData['meters']) && $holeData['meters'] !== '' ? (int) $holeData['meters'] : null,
                ]);
                $par += (int) ($holeData['par'] ?? 3);
            }

            $game->par = $par;
            $game->holes_count = count($holes);
            $game->save();

            DB::commit();

            return Response::json(GameResource::make(
                $game->fresh(['course', 'layout', 'holes', 'players.player', 'players.scores'])
            ));
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['error' => 'An error occurred while creating the game.'], 500);
        }
    }

    /**
     * Step 2 — set the players and their handicaps (also used for quick retro total-throws entry).
     */
    public function players(Request $request, int $id): JsonResponse
    {
        $game = $this->resolveGame($request, $id);

        $validator = Validator::make($request->all(), [
            'players' => 'required|array|min:1',
            'players.*.player_id' => 'required|exists:players,id',
            'players.*.handicap' => 'nullable|integer',
            'players.*.total_throws' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        DB::beginTransaction();
        try {
            $keptIds = [];
            foreach ($request->get('players') as $index => $playerData) {
                $gamePlayer = GamePlayer::firstOrNew([
                    'game_id' => $game->id,
                    'player_id' => $playerData['player_id'],
                ]);
                $gamePlayer->handicap = (int) ($playerData['handicap'] ?? 0);
                if (array_key_exists('total_throws', $playerData) && $playerData['total_throws'] !== null) {
                    $gamePlayer->total_throws = (int) $playerData['total_throws'];
                }
                $gamePlayer->position = $index;
                $gamePlayer->save();
                $keptIds[] = $gamePlayer->id;
            }

            // Remove players no longer in the game (cascades their hole scores)
            $game->players()->whereNotIn('id', $keptIds)->delete();

            DB::commit();

            return Response::json(GameResource::make(
                $game->fresh(['course', 'layout', 'holes', 'players.player', 'players.scores'])
            ));
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['error' => 'An error occurred while saving players.'], 500);
        }
    }

    /**
     * Start the live game.
     */
    public function start(Request $request, int $id): JsonResponse
    {
        $game = $this->resolveGame($request, $id);
        $game->status = 'in_progress';
        $game->save();

        return Response::json(GameResource::make(
            $game->fresh(['course', 'layout', 'holes', 'players.player', 'players.scores'])
        ));
    }

    /**
     * Autosave hole scores during the live game. Recomputes affected players' total throws.
     */
    public function scores(Request $request, int $id): JsonResponse
    {
        $game = $this->resolveGame($request, $id);

        $validator = Validator::make($request->all(), [
            'scores' => 'required|array|min:1',
            'scores.*.game_player_id' => 'required|integer',
            'scores.*.game_hole_id' => 'required|integer',
            'scores.*.throws' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        $playerIds = $game->players()->pluck('id')->toArray();
        $holeIds = $game->holes()->pluck('id')->toArray();

        DB::beginTransaction();
        try {
            $affectedPlayerIds = [];
            foreach ($request->get('scores') as $score) {
                $gamePlayerId = (int) $score['game_player_id'];
                $gameHoleId = (int) $score['game_hole_id'];

                // Guard: both must belong to this game
                if (! in_array($gamePlayerId, $playerIds, true) || ! in_array($gameHoleId, $holeIds, true)) {
                    continue;
                }

                GameHoleScore::updateOrCreate(
                    ['game_player_id' => $gamePlayerId, 'game_hole_id' => $gameHoleId],
                    ['game_id' => $game->id, 'throws' => isset($score['throws']) && $score['throws'] !== '' ? (int) $score['throws'] : null]
                );
                $affectedPlayerIds[$gamePlayerId] = true;
            }

            // Recompute total_throws for affected players
            foreach (array_keys($affectedPlayerIds) as $gamePlayerId) {
                $total = GameHoleScore::where('game_player_id', $gamePlayerId)->sum('throws');
                GamePlayer::where('id', $gamePlayerId)->update(['total_throws' => (int) $total]);
            }

            DB::commit();

            return Response::json(GameResource::make(
                $game->fresh(['course', 'layout', 'holes', 'players.player', 'players.scores'])
            ));
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['error' => 'An error occurred while saving scores.'], 500);
        }
    }

    /**
     * Finish the game. Recomputes total throws from hole scores where present; otherwise keeps
     * the directly entered total (retro quick entry).
     */
    public function complete(Request $request, int $id): JsonResponse
    {
        $game = $this->resolveGame($request, $id);

        DB::beginTransaction();
        try {
            if ($request->has('note')) {
                $game->note = $request->get('note');
            }

            foreach ($game->players as $gamePlayer) {
                $scoreCount = GameHoleScore::where('game_player_id', $gamePlayer->id)
                    ->whereNotNull('throws')
                    ->count();
                if ($scoreCount > 0) {
                    $total = GameHoleScore::where('game_player_id', $gamePlayer->id)->sum('throws');
                    $gamePlayer->total_throws = (int) $total;
                    $gamePlayer->save();
                }
            }

            $game->status = 'completed';
            $game->save();

            DB::commit();

            return Response::json(GameResource::make(
                $game->fresh(['course', 'layout', 'holes', 'players.player', 'players.scores'])
            ));
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['error' => 'An error occurred while completing the game.'], 500);
        }
    }

    /**
     * Resolve holes for a new game: prefer the chosen layout's holes, else the custom payload.
     */
    private function resolveHoles(Request $request): array
    {
        if ($request->filled('course_layout_id')) {
            $layout = CourseLayout::with('holes')->find($request->get('course_layout_id'));
            if ($layout && $layout->holes->count() > 0) {
                return $layout->holes->map(fn ($h) => [
                    'number' => $h->number,
                    'par' => $h->par,
                    'meters' => $h->meters,
                ])->toArray();
            }
        }

        return $request->get('holes', []);
    }

    private function resolveGame(Request $request, int $id): Game
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $game = Game::with(['holes', 'players'])
            ->whereRelation('sites', 'site_id', $siteId)
            ->find($id);

        if (! $game) {
            App::abort(404);
        }

        return $game;
    }
}
