<?php

namespace App\Http\Controllers;

use App\Models\Activity\Activity;
use App\Models\Activity\UserActivity;
use App\Models\Contact\Contact;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function dashboard(Request $request): JsonResponse
    {
        $lastAddedContacts = Contact::without(['phase', 'source', 'tasks'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->where('user_id', $request->user()->id)
            ->get();

        $contactsToCall = Contact::without(['phase', 'source', 'tasks'])
            ->whereDate('next_meeting', now()->toDateString())
            ->where('user_id', $request->user()->id)
            ->get();

        $comingEvents = Contact::without(['phase', 'source', 'tasks'])
            ->whereDate('next_meeting', '>=', now()->toDateString())
            ->orderBy('next_meeting')
            ->limit(5)
            ->where('user_id', $request->user()->id)
            ->get();

        return Response::json([
            'lastAddedContacts' => [
                'data' => $lastAddedContacts
            ],
            'contactsToCall' => [
                'data' => $contactsToCall
            ],
            'comingEvents' => [
                'data' => $comingEvents
            ]
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $daysMonths = now()->daysInMonth;
        if ($request->has('filter')) {
            if ($request->get('filter') == 'month' && $request->has('year')) {
                $daysMonths = Carbon::createFromDate($request->year, $request->month)->daysInMonth;
            } else {
                $daysMonths = now()->daysInMonth;
            }
        }

        $businessGrowthActivityIds = [1, 6, 7, 8, 9, 10, 11, 12, 21, 22];
        $personalGrowthActivityIds = [2, 3, 4, 5, 16, 17, 18];

        $businessActivitiesQuery = UserActivity::with('activity')
            ->selectRaw('activity_id, COUNT(*) as count, DATE_FORMAT(date, "%e. %c.") as day')
            ->where('user_id', $request->user()->id)
            ->whereIn('activity_id', $businessGrowthActivityIds)
            ->groupBy('activity_id', 'day');

        if ($request->has('filter')) {
            if ($request->get('filter') == 'month') {
                if ($request->has('month') && $request->has('year')) {
                    $businessActivitiesQuery->whereMonth('date', (int)$request->month)
                        ->whereYear('date', $request->year);
                } else {
                    $businessActivitiesQuery->whereMonth('date', now()->month)
                        ->whereYear('date', now()->year);
                }
            } else if ($request->get('filter') == 'year') {
                if ($request->has('year')) {
                    $businessActivitiesQuery->whereYear('date', $request->year);
                } else {
                    $businessActivitiesQuery->whereYear('date', now()->year);
                }
            }
        }

        $businessActivities = $businessActivitiesQuery->get();

        $rawActivities = [];
        $activities = Activity::query()
            ->whereIn('id', $businessGrowthActivityIds)
            ->get();

        $colors = [];
        $rawColors = [];
        foreach ($activities as $activity) {
            $rawActivities[$activity->id] = $activity->name;
            $colors[$activity->name] = $this->getColorCode($activity->color);
            $rawColors[] = $this->getColorCode($activity->color);
        }

        $series = [];
        $activityData = [];

        foreach ($rawActivities as $activityName) {
            $activityData[$activityName] = array_fill(0, $daysMonths, 0);
        }

        foreach ($businessActivities as $activity) {
            $activityName = $rawActivities[$activity->activity_id];
            $day = Carbon::createFromFormat('j. n.', $activity->day)->day;
            $activityData[$activityName][$day] = $activity->count;
        }

        foreach ($activityData as $name => $data) {
            $series[] = [
                'name' => $name,
                'data' => $data,
                'color' => $colors[$name]
            ];
        }

        $axis = [];
        for ($day = 1; $day <= $daysMonths; $day++) {
            $axis[] = $day . '. ' . now()->month . '.';
        }

        return Response::json([
            'business' => [
                'series' => $series,
                'axis' => $axis
            ],
            'businessSummary' => [],
            'colors' => $rawColors
        ]);
    }

    private function getColorCode(string $color): string
    {
        $code = '#020617';

        switch ($color) {
            case 'slate':
                $code = '#64748b';
                break;
            case 'gray':
                $code = '#6b7280';
                break;
            case 'zinc':
                $code = '#71717a';
                break;
            case 'neutral':
                $code = '#737373';
                break;
            case 'stone':
                $code = '#78716c';
                break;
            case 'red':
                $code = '#ef4444';
                break;
            case 'orange':
                $code = '#f97316';
                break;
            case 'amber':
                $code = '#f59e0b';
                break;
            case 'yellow':
                $code = '#eab308';
                break;
            case 'lime':
                $code = '#84cc16';
                break;
            case 'green':
                $code = '#22c55e';
                break;
            case 'emerald':
                $code = '#10b981';
                break;
            case 'teal':
                $code = '#14b8a6';
                break;
            case 'cyan':
                $code = '#06b6d4';
                break;
            case 'sky':
                $code = '#0ea5e9';
                break;
            case 'blue':
                $code = '#3b82f6';
                break;
            case 'indigo':
                $code = '#6366f1';
                break;
            case 'violet':
                $code = '#8b5cf6';
                break;
            case 'purple':
                $code = '#a855f7';
                break;
            case 'fuchsia':
                $code = '#d946ef';
                break;
            case 'pink':
                $code = '#ec4899';
                break;
            case 'rose':
                $code = '#f43f5e';
                break;
        }

        return $code;
    }
}
