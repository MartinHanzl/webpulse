<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdministratorResource;
use App\Models\Administrator;
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
            'email' => 'required|unique:employees',
            'phone' => 'required|unique:employees',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'sucess' => false,
                'errors' => $validator->messages()
            ], 400);
        }

        $admin = new Administrator();

        $adminData = [
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'phone_prefix' => $request->get('phone_prefix') ?? '+420',
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'role' => $request->get('role') ?? 'editor',
            'active' => 1
        ];

        $admin->fill($adminData);
        $admin->save();

        return Response::json(
            AdministratorResource::make($admin)
        );
    }
}
