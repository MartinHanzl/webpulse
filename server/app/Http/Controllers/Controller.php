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
        $daysInMonth = now()->daysInMonth;

        $businessGrowthActivityIds = [1, 6, 7, 8, 9, 10, 11, 12, 21, 22];
        $personalGrowthActivityIds = [2, 3, 4, 5, 16, 17, 18];

        $businessActivities = UserActivity::with('activity')
            ->selectRaw('activity_id, COUNT(*) as count, DATE_FORMAT(date, "%e. %c.") as day')
            ->where('user_id', $request->user()->id)
            ->whereIn('activity_id', $businessGrowthActivityIds)
            ->whereMonth('date', now()->month)
            ->groupBy('activity_id', 'day')
            ->get();

        $rawActivities = Activity::query()
            ->whereIn('id', $businessGrowthActivityIds)
            ->pluck('name', 'id');

        $series = [];
        $activityData = [];

        foreach ($rawActivities as $activityName) {
            $activityData[$activityName] = array_fill(0, $daysInMonth, 0);
        }

        foreach ($businessActivities as $activity) {
            $activityName = $rawActivities[$activity->activity_id];
            $day = (int)date('j', strtotime($activity->day));
            $activityData[$activityName][$day] = $activity->count;
        }

        foreach ($activityData as $name => $data) {
            $series[] = [
                'name' => $name,
                'data' => $data,
                'color' => '#14b8a6', // You can customize the color as needed
            ];
        }

        $axis = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $axis[] = $day . '. ' . now()->month . '.';
        }

        return Response::json([
            'business' => [
                'series' => $series,
                'axis' => $axis
            ]
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
