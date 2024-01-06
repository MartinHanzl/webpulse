<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'sucess' => false,
                'errors' => $validator->messages()
            ], 400);
        }

        $user = new User();

        $userData = [
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'phone_prefix' => $request->get('phone_prefix') ?? '+420',
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'verification_code' => Str::random(64),
            'invitation_code' => $this->generateCode(6),
            'active' => 1, // for developer purposes only
            'verified' => 1 // for developer purposes only
        ];

        $user->fill($userData);
        $user->save();

        return Response::json();
    }

    private function generateCode(int $length)
    {
        $code = strtoupper(Str::random(6));

        $check = User::where('invitation_code', '=', $code)->first();
        if ($check) {
            self::generateCode($length);
        }

        return $code;
    }
}
