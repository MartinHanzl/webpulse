<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contact\ContactResource;
use App\Http\Resources\Contact\ContactSimpleResource;
use App\Models\Contact\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Contact::query()
            ->with(['contact'])
            ->where('user_id', $request->user()->id);

        if ($request->has('search') && $request->get('search') != '' && $request->get('search') != null) {
            $searchString = $request->get('search');
            if (str_contains(':', $searchString)) {
                $searchString = explode(':', $searchString);
                $query->where($searchString[0], 'like', '%' . $searchString[1] . '%');
            } else {
                $query->where('firstname', 'like', '%' . $searchString . '%')
                    ->orWhere('lastname', 'like', '%' . $searchString . '%')
                    ->orWhere('phone', 'like', '%' . $searchString . '%')
                    ->orWhere('email', 'like', '%' . $searchString . '%')
                    ->orWhere('company', 'like', '%' . $searchString . '%')
                    ->orWhere('street', 'like', '%' . $searchString . '%')
                    ->orWhere('city', 'like', '%' . $searchString . '%')
                    ->orWhere('zip', 'like', '%' . $searchString . '%')
                    ->orWhere('occupation', 'like', '%' . $searchString . '%')
                    ->orWhere('goal', 'like', '%' . $searchString . '%');
            }
        }
        if($request->has('filters')) {
            $rawFilters = json_decode($request->get('filters'));
            switch ($rawFilters->slug) {
                case 'phase':
                    $query->whereIn('contact_phase_id', $rawFilters->values);
                    break;
                case 'source':
                    $query->whereIn('contact_source_id', $rawFilters->values);
                    break;
                case 'contact':
                    $query->where('contact_id', $rawFilters->values);
                    break;
            }
        }

        if ($request->has('orderWay') && $request->get('orderBy')) {
            $query->orderBy($request->get('orderBy'), $request->get('orderWay'));
        }

        if ($request->has('paginate')) {
            $contacts = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => ContactSimpleResource::collection($contacts->items()),
                'total' => $contacts->total(),
                'perPage' => $contacts->perPage(),
                'currentPage' => $contacts->currentPage(),
                'lastPage' => $contacts->lastPage(),
            ]);
        }

        $contacts = $query->get();
        return Response::json(ContactSimpleResource::collection($contacts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $id = null): JsonResponse
    {
        if ($id) {
            $contact = Contact::find($id);
            if (!$contact) {
                App::abort(404);
            }
        } else {
            $contact = new Contact();
        }

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'goal' => 'nullable|string|max:255',
            'contact_phase_id' => 'nullable|integer|exists:contact_phases,id',
            'contact_source_id' => 'nullable|integer|exists:contact_sources,id',
            'contact_id' => 'nullable|integer|exists:contacts,id',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $contact->fill($request->all());
            $contact->user_id = $request->user()->id;

            /*$contact->contact_phase_id = $request->has('contact_phase_id') ? $request->get('contact_phase_id') : null;
            $contact->contact_source_id = $request->has('contact_source_id') ? $request->get('contact_source_id') : null;*/

            if($request->has('formatted_last_contacted_at') && $request->get('formatted_last_contacted_at') != null) {
                $contact->last_contacted_at = Carbon::parse($request->get('formatted_last_contacted_at'));
            } else {
                $contact->last_contacted_at = null;
            }

            if($request->has('formatted_next_meeting') && $request->get('formatted_next_meeting') != null) {
                $contact->next_meeting = Carbon::parse($request->get('formatted_next_meeting'));
            } else {
                $contact->next_meeting = null;
            }

            $contact->syncTasks($request);
            $contact->save();

            DB::commit();
        } catch (\Throwable | \Exception $e) {
            DB::rollBack();
            return Response::json(['message' => 'An error occurred while updating contact.'], 500);
        }

        return Response::json(ContactResource::make($contact));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        if (!$id) {
            App::abort(400);
        }

        $contact = Contact::with(['contact', 'source', 'phase', 'tasks', 'histories'])->find($id);
        if (!$contact) {
            App::abort(404);
        }

        return Response::json(ContactResource::make($contact));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if (!$id) {
            App::abort(400);
        }

        $contact = Contact::find($id);
        if (!$contact) {
            App::abort(404);
        }

        $contact->delete();
        return Response::json();
    }
}
