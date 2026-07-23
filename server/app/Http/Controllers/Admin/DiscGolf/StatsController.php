<?php

namespace App\Http\Controllers\Admin\DiscGolf;

use App\Http\Controllers\Controller;
use App\Models\DiscGolf\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class StatsController extends Controller
{
    /**
     * Per-player overall statistics across all courses.
     */
    public function players(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $games = $this->loadGames($siteId);

        // player_id => chronological list of results
        $byPlayer = [];
        $names = [];
        foreach ($games as $game) {
            foreach ($game->players as $gp) {
                if (! $gp->player || ! $gp->player->include_in_stats) {
                    continue;
                }
                $byPlayer[$gp->player_id][] = $this->result($game, $gp);
                $names[$gp->player_id] = $gp->player?->name;
            }
        }

        $rows = [];
        foreach ($byPlayer as $playerId => $results) {
            $stats = $this->computeStats($results);
            $rows[] = array_merge([
                'player_id' => $playerId,
                'player_name' => $names[$playerId] ?? null,
            ], $stats);
        }

        // Sort by best average +/- par (ascending — lower is better)
        usort($rows, fn ($a, $b) => ($a['avg_relative'] ?? 999) <=> ($b['avg_relative'] ?? 999));

        return Response::json(['data' => $rows]);
    }

    /**
     * Per-course statistics: one table per course, each with per-player rows + recommended handicap.
     */
    public function courses(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $games = $this->loadGames($siteId);

        // Group games by course NAME (so a custom one-off with the same name as a
        // real course merges into one table).
        $courses = [];
        foreach ($games as $game) {
            $name = $game->course_name ?? '—';
            $key = mb_strtolower(trim($name));
            if (! isset($courses[$key])) {
                $courses[$key] = [
                    'course_id' => $game->course_id,
                    'course_name' => $name,
                    'byPlayer' => [],
                    'names' => [],
                ];
            }
            foreach ($game->players as $gp) {
                if (! $gp->player || ! $gp->player->include_in_stats) {
                    continue;
                }
                $courses[$key]['byPlayer'][$gp->player_id][] = $this->result($game, $gp);
                $courses[$key]['names'][$gp->player_id] = $gp->player?->name;
            }
        }

        $data = [];
        foreach ($courses as $course) {
            $rows = [];
            foreach ($course['byPlayer'] as $playerId => $results) {
                $stats = $this->computeStats($results);
                $stats['recommended_handicap'] = $this->recommendedHandicap($results);
                $rows[] = array_merge([
                    'player_id' => $playerId,
                    'player_name' => $course['names'][$playerId] ?? null,
                ], $stats);
            }
            usort($rows, fn ($a, $b) => ($a['avg_relative'] ?? 999) <=> ($b['avg_relative'] ?? 999));

            $data[] = [
                'course_id' => $course['course_id'],
                'course_name' => $course['course_name'],
                'players' => $rows,
            ];
        }

        return Response::json(['data' => $data]);
    }

    private function loadGames(int $siteId): Collection
    {
        return Game::with(['players.player'])
            ->whereRelation('sites', 'site_id', $siteId)
            ->where('status', 'completed')
            ->orderBy('played_at')
            ->get();
    }

    /**
     * Normalize one game+player into a result record.
     */
    private function result(Game $game, $gp): array
    {
        $score = (int) $gp->total_throws;
        $handicap = (int) $gp->handicap;
        $par = (int) $game->par;
        $net = $score - $handicap;

        return [
            'game_id' => $game->id,
            'played_at' => $game->played_at?->toDateString(),
            'score' => $score,
            'handicap' => $handicap,
            'par' => $par,
            'net' => $net,
            // +/- par from the net (handicap-adjusted) score
            'relative' => $net - $par,
            // gross +/- par (no handicap) — used for the recommended handicap
            'gross_relative' => $score - $par,
        ];
    }

    /**
     * Compute the statistics bundle from a chronological (oldest→newest) result list.
     */
    private function computeStats(array $results): array
    {
        $count = count($results);
        if ($count === 0) {
            return $this->emptyStats();
        }

        $scores = array_column($results, 'score');
        $relatives = array_column($results, 'relative');

        // Newest → oldest for "last N" windows
        $reversed = array_reverse($relatives);
        $last5 = array_slice($reversed, 0, 5);
        $last10 = array_slice($reversed, 0, 10);

        return [
            'games_played' => $count,
            'best_score' => min($scores),
            'avg_score' => round(array_sum($scores) / $count, 1),
            'worst_score' => max($scores),
            'best_relative' => min($relatives),
            'avg_relative' => round(array_sum($relatives) / $count, 1),
            'last5_relative' => round(array_sum($last5) / count($last5), 1),
            'last10_relative' => round(array_sum($last10) / count($last10), 1),
            'trend' => $this->trend($reversed),
            'chart' => array_map(fn ($r) => [
                'game_id' => $r['game_id'],
                'date' => $r['played_at'],
                'relative' => $r['relative'],
            ], $results),
        ];
    }

    /**
     * Trend from recent vs previous form. Lower +/- par is better.
     * Compares avg of last 3 vs the preceding 3; threshold ~1 stroke.
     */
    private function trend(array $reversedRelatives): string
    {
        if (count($reversedRelatives) < 4) {
            return 'stable';
        }

        $recent = array_slice($reversedRelatives, 0, 3);
        $previous = array_slice($reversedRelatives, 3, 3);
        if (count($previous) === 0) {
            return 'stable';
        }

        $recentAvg = array_sum($recent) / count($recent);
        $previousAvg = array_sum($previous) / count($previous);
        $diff = $recentAvg - $previousAvg;

        if ($diff <= -1) {
            return 'improving';
        }
        if ($diff >= 1) {
            return 'worsening';
        }

        return 'stable';
    }

    /**
     * Recommended handicap = weighted average of gross +/- par over the last 5 games on the course,
     * weighting more recent games higher. Clamped to >= 0.
     */
    private function recommendedHandicap(array $results): int
    {
        if (count($results) === 0) {
            return 0;
        }

        // Newest → oldest, take up to 5
        $recent = array_slice(array_reverse($results), 0, 5);
        $weightedSum = 0;
        $weightTotal = 0;
        $n = count($recent);
        foreach ($recent as $index => $r) {
            $weight = $n - $index; // most recent gets highest weight
            $weightedSum += $r['gross_relative'] * $weight;
            $weightTotal += $weight;
        }

        $avg = $weightTotal > 0 ? $weightedSum / $weightTotal : 0;

        return (int) max(0, round($avg));
    }

    private function emptyStats(): array
    {
        return [
            'games_played' => 0,
            'best_score' => null,
            'avg_score' => null,
            'worst_score' => null,
            'best_relative' => null,
            'avg_relative' => null,
            'last5_relative' => null,
            'last10_relative' => null,
            'trend' => 'stable',
            'chart' => [],
        ];
    }
}
