<?php

namespace App\Http\Controllers;

use App\Models\Activity\UserActivity;
use App\Models\Contact\Contact;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
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
        $businessGrowthActivityIds = [1, 6, 7, 8, 9, 10, 11, 12, 21, 22];
        $personalGrowthActivityIds = [2, 3, 4, 5, 16, 17, 18];

        $businessActivities = UserActivity::selectRaw('activity_id, COUNT(*) as count, DATE_FORMAT(date, "%e. %c.") as day')
            ->where('user_id', $request->user()->id)
            ->whereIn('activity_id', $businessGrowthActivityIds)
            ->whereMonth('date', now()->month)
            ->groupBy('activity_id', 'day')
            ->get();

        $personalActivities = UserActivity::selectRaw('activity_id, COUNT(*) as count, DATE_FORMAT(date, "%e. %c.") as day')
            ->where('user_id', $request->user()->id)
            ->whereIn('activity_id', $personalGrowthActivityIds)
            ->whereMonth('date', now()->month)
            ->groupBy('activity_id', 'day')
            ->get();

        return Response::json([
            'business' => [
                'name' => 'Business by months',
                'data' => $businessActivities
            ],
            'personal' => [
                'name' => 'Personal by months',
                'data' => $personalActivities
            ]
        ]);
    }
}
