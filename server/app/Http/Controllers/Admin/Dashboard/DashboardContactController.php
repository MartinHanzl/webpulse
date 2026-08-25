<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Contact\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DashboardContactController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $lastAddedContacts = Contact::without(['phase', 'source', 'tasks'])
            ->orderBy('created_at', 'desc')
            ->where('user_id', $request->user()->id)
            ->limit(10)
            ->get();

        $contactsToCall = Contact::without(['phase', 'source', 'tasks'])
            ->whereDate('next_contact', now()->toDateString())
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $comingEvents = Contact::without(['phase', 'source', 'tasks'])
            ->whereDate('next_meeting', '>=', now()->toDateString())
            ->orderBy('next_meeting')
            ->where('user_id', $request->user()->id)
            ->get();

        return Response::json([
            'lastAddedContacts' => [
                'data' => $lastAddedContacts,
            ],
            'contactsToCall' => [
                'data' => $contactsToCall,
            ],
            'comingEvents' => [
                'data' => $comingEvents,
            ],
        ]);
    }
}
