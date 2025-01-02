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
        $activityByMonths = UserActivity::selectRaw('MONTH(date) as month, COUNT(id) as count')
            ->where('user_id', $request->user()->id)
            ->groupBy('month')
            ->get();

        $activityByYears = UserActivity::selectRaw('YEAR(date) as year, COUNT(id) as count')
            ->where('user_id', $request->user()->id)
            ->groupBy('year')
            ->get();

        return Response::json([
            'activity' => [
                'byMonths' => [
                    'name' => 'Activity by months',
                    'data' => $activityByMonths
                ],
                'byYears' => [
                    'name' => 'Activity by years',
                    'data' => $activityByYears
                ]
            ]
        ]);
    }
}
