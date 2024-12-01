<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contact\ContactResource;
use App\Http\Resources\Contact\ContactSimpleResource;
use App\Models\Contact\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            ->with(['contacts'])
            ->where('user_id', $request->user()->id);

        if ($request->has('search') && $request->get('search') != '' && $request->get('search') != null) {
            $searchString = $request->get('search');
            if (str_contains(':', $searchString)) {
                $searchString = explode(':', $searchString);
                $query->where($searchString[0], 'like', '%' . $searchString[1] . '%');
            } else {
                $query->where('name', 'like', '%' . $searchString . '%');
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
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $contact->fill($request->all());
            $contact->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json(['message' => 'An error occurred while updating contact phase.'], 500);
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

        $contact = Contact::find($id);
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