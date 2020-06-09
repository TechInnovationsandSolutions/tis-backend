<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = $request->user();
            $success = $user->createToken('Personal Access Token')->accessToken;

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

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function register(Request $request)
    {

        $user = User::create($request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'nullable',
            'address' => 'nullable',
            'password' => 'required|min:6|confirmed',
        ]));

         $success = $user->createToken('Personal Access Token')->accessToken;

            $user['token'] = $success;


        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Account Created Successfully',
            'data' => new UserResource($user),
        ], 201);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $user->update($request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            //'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]));

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Account Updated Successfully',
            'data' => new UserResource($user),
        ], 201);
    }

    public function show()
    {
        $user = auth()->user();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Account Details',
            'data' => new UserResource($user),
        ], 201);
    }

    public function passwordReset(Request $request)
    {
        if($pr = DB::table('password_resets')->where('email', $request->email)->first()){
            if (Carbon::parse($pr->created_at)->addHour() > Carbon::now() && Hash::check($request->token, $pr->token)) {
                $user = User::where('email', $pr->email)->first();
                        return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Token valid',
                    'data' => new UserResource($user),
                ], 201);
             }else{
                    return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Token Expired',
                    'data' => false,
                ], 201);
             }
    }else{
        return response()->json([
            'status' => 'error',
            'code' => 401,
            'message' => 'Invalid Token',
            'data' => false,
        ], 401);
    }
    }

    public function passwordChange(Request $request)
    {   
        if($user = User::find($request->user_id)){
            $user->update([
                'password' => bcrypt($request->password)
            ]);

            return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Token valid',
                    'data' => 'Password updated, you can now login',
                ], 200);        
        }else{
            return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'User not found',
                    'data' => 'Password cannot be changed, user does not exist',
                ], 200);   
        }
    }
}
