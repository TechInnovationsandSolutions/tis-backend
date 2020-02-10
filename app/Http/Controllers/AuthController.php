<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success = $user->createToken('userClient')->accessToken;

            $user['token'] = $success;

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Login Successful',
                'data' => new UserResource($user),
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create($request->all());

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'User created',
            'data' => new UserResource($user),
        ], 201);
    }
}
