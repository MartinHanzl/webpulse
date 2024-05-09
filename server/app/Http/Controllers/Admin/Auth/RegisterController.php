<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdministratorResource;
use App\Models\Administrator\Administrator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        /*if (Auth::user()->role == 'editor') {
            App::abort(400);
        }*/

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:administrators',
            'phone' => 'required|unique:administrators',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'sucess' => false,
                'errors' => $validator->messages()
            ], 400);
        }

        $admin = new Administrator();
        $admin->fill([
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'phone_prefix' => $request->get('phone_prefix') ?? '+420',
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'role' => $request->get('group_id') ?? 1,
            'active' => 1
        ]);
        $admin->save();

        return Response::json(
            AdministratorResource::make($admin)
        );
    }
}
