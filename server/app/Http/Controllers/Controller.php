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
        $code = '';

        return $code;
    }
}
