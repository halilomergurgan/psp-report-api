<?php

namespace App\Http\Controllers\API\v3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Enums\StatusEnum;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => StatusEnum::DECLINED->value,
                'error' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'token' => $token,
            'status' => StatusEnum::APPROVED->value,
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}

