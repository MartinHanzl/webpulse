<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Activity\Activity;
use App\Models\Activity\UserActivity;
use App\Models\Cashflow\Cashflow;
use App\Models\Cashflow\CashflowCategory;
use App\Models\Contact\ContactPhase;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;

class StatisticsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->user()?->id !== 1) {
            App::abort(403);
        }

        $daysInPeriod = $this->resolveDaysInPeriod($request);
        $axis = $this->buildAxis($request, $daysInPeriod);

        $business = $this->buildActivitySeries($request, $this->activityIds('is_business'), $daysInPeriod);
        $personal = $this->buildActivitySeries($request, $this->activityIds('is_personal'), $daysInPeriod);
        $cashflow = $this->buildCashflowChart($request);

        return Response::json([
            'contacts' => $this->buildContactsSeries($request),
            'business' => [
                'series' => $business['series'],
                'axis' => $axis,
            ],
            'personal' => [
                'series' => $personal['series'],
                'axis' => $axis,
            ],
            'cashflow' => $cashflow['data'],
            'cashflowSummary' => $cashflow['summary'],
            'businessSummary' => [],
            'businessColors' => $business['colors'],
            'personalColors' => $personal['colors'],
        ]);
    }

    private function activityIds(string $flagColumn): array
    {
        return Activity::query()->where($flagColumn, true)->pluck('id')->toArray();
    }

    private function resolveDaysInPeriod(Request $request): int
    {
        if ($request->get('filter') === 'month' && $request->has('year')) {
            return Carbon::createFromDate($request->year, $request->month)->daysInMonth;
        }

        if ($request->get('filter') === 'year') {
            return 12;
        }

        return now()->daysInMonth;
    }

    private function buildAxis(Request $request, int $daysInPeriod): array
    {
        if ($request->get('filter') === 'month' && $request->has('year')) {
            return array_map(fn (int $day) => "{$day}. {$request->month}.", range(1, $daysInPeriod));
        }

        if ($request->get('filter') === 'year') {
            return array_map(fn (int $month) => "{$month}.", range(1, 12));
        }

        return [];
    }

    /**
     * Applies the request's month/year (or plain year) filter to a date column.
     */
    private function applyPeriodFilter(Builder $query, Request $request, string $column): void
    {
        if (! $request->has('filter')) {
            return;
        }

        if ($request->get('filter') === 'month') {
            $month = $request->has('month') ? $request->month : now()->month;
            $year = $request->has('year') ? $request->year : now()->year;
            $query->whereMonth($column, $month)->whereYear($column, $year);
        } elseif ($request->get('filter') === 'year') {
            $query->whereYear($column, $request->has('year') ? $request->year : now()->year);
        }
    }

    private function applyBudgetPeriodFilter(Builder $query, Request $request): void
    {
        $this->applyPeriodFilter($query, $request, 'start_date');
        $this->applyPeriodFilter($query, $request, 'end_date');
    }

    private function buildContactsSeries(Request $request): array
    {
        $contactPhases = ContactPhase::query()
            ->where('user_id', $request->user()->id)
            ->where('show_in_statistics', true)
            ->withCount(['contacts' => function ($query) use ($request) {
                if ($request->has('from')) {
                    $query->whereDate('created_at', '>=', Carbon::parse($request->from));
                }
                if ($request->has('to')) {
                    $query->whereDate('created_at', '<=', Carbon::parse($request->to));
                }
            }])
            ->orderBy('position')
            ->get();

        $series = [0];
        $axis = ['Celkem'];

        foreach ($contactPhases as $contactPhase) {
            $series[] = $contactPhase->contacts_count;
            $axis[] = $contactPhase->name;
            $series[0] += $contactPhase->contacts_count;
        }

        return [
            'series' => $series,
            'axis' => $axis,
        ];
    }

    private function buildActivitySeries(Request $request, array $activityIds, int $daysInPeriod): array
    {
        $activitiesQuery = UserActivity::query()
            ->where('user_id', $request->user()->id)
            ->whereIn('activity_id', $activityIds)
            ->groupBy('activity_id', 'day');

        $this->applyPeriodFilter($activitiesQuery, $request, 'date');

        $dayFormat = $request->get('filter') === 'year' ? '%c.' : '%e. %c.';
        $activitiesQuery->selectRaw('activity_id, COUNT(*) as count, DATE_FORMAT(date, ?) as day', [$dayFormat]);

        $activities = $activitiesQuery->get();
        $rawActivities = Activity::query()->whereIn('id', $activityIds)->get();

        $names = [];
        $colors = [];
        $rawColors = [];
        $data = [];

        foreach ($rawActivities as $activity) {
            $color = $this->getColorCode($activity->color);

            $names[$activity->id] = $activity->name;
            $colors[$activity->name] = $color;
            $rawColors[] = $color;
            $data[$activity->name] = array_fill(0, $daysInPeriod, 0);
        }

        foreach ($activities as $activity) {
            $activityName = $names[$activity->activity_id];
            $day = $request->get('filter') === 'year'
                ? (int) $activity->day - 1
                : (int) explode('. ', $activity->day)[0] - 1;

            $data[$activityName][$day] = $activity->count;
        }

        $series = [];
        foreach ($data as $name => $values) {
            $series[] = [
                'name' => $name,
                'data' => $values,
                'color' => $colors[$name],
            ];
        }

        return [
            'series' => $series,
            'colors' => $rawColors,
        ];
    }

    private function buildCashflowChart(Request $request): array
    {
        $userId = $request->user()->id;

        $categories = CashflowCategory::with([
            'cashflows' => fn ($query) => $this->applyPeriodFilter($query, $request, 'date'),
            'budgets' => fn ($query) => $this->applyBudgetPeriodFilter($query, $request),
        ])
            ->where('user_id', $userId)
            ->get();

        $incomeQuery = Cashflow::query()
            ->where('user_id', $userId)
            ->where('type', 'income');
        $this->applyPeriodFilter($incomeQuery, $request, 'date');
        $totalIncome = round((float) $incomeQuery->sum('amount'), 2);

        $chartData = [];
        $totalExpense = 0.0;

        foreach ($categories as $category) {
            $spent = round($category->cashflows->sum('amount'), 2);
            $budget = round($category->budgets->sum('amount'), 2);
            $totalExpense += $spent;

            $percentageLeft = $budget > 0 ? (($budget - $spent) / $budget) * 100 : 100;

            $chartData[] = [
                'x' => $category->name,
                'y' => $spent,
                'goals' => [$this->buildBudgetGoalMarker($category->name, $budget, $percentageLeft)],
                'percentage' => $percentageLeft,
            ];
        }

        return [
            'data' => $chartData,
            'summary' => [
                'income' => $totalIncome,
                'expense' => round($totalExpense, 2),
                'difference' => round($totalIncome - $totalExpense, 2),
            ],
        ];
    }

    private function buildBudgetGoalMarker(string $name, float $budget, float $percentageLeft): array
    {
        $marker = [
            'name' => $name,
            'value' => $budget,
            'strokeColor' => '#7f1d1d',
        ];

        if ($percentageLeft <= 0) {
            return $marker + [
                'strokeHeight' => 12,
                'strokeWidth' => 0,
                'strokeLineCap' => 'round',
            ];
        }

        if ($percentageLeft <= 25) {
            return $marker + [
                'strokeHeight' => 1,
                'strokeDashArray' => 2,
            ];
        }

        return $marker + ['strokeHeight' => 1];
    }

    private function getColorCode(string $color): string
    {
        return match ($color) {
            'slate' => '#64748b',
            'gray' => '#6b7280',
            'zinc' => '#71717a',
            'neutral' => '#737373',
            'stone' => '#78716c',
            'red' => '#ef4444',
            'orange' => '#f97316',
            'amber' => '#f59e0b',
            'yellow' => '#eab308',
            'lime' => '#84cc16',
            'green' => '#22c55e',
            'emerald' => '#10b981',
            'teal' => '#14b8a6',
            'cyan' => '#06b6d4',
            'sky' => '#0ea5e9',
            'blue' => '#3b82f6',
            'indigo' => '#6366f1',
            'violet' => '#8b5cf6',
            'purple' => '#a855f7',
            'fuchsia' => '#d946ef',
            'pink' => '#ec4899',
            'rose' => '#f43f5e',
            default => '#020617',
        };
    }
}
