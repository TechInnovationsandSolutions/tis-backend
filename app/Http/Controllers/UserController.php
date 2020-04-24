<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as ResourcesUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Users',
            'data' => ResourcesUser::collection(User::all()),
        ], 200);
    }

    public function store(Request $request)
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
            'data' => new ResourcesUser($user),
        ], 201);
    }

    public function show(User $id)
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'User',
            'data' => new ResourcesUser($id),
        ], 200);
    }

    public function update(User $id, Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);


        $id->update($request->all());
        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'User updated',
            'data' => new ResourcesUser($id),
        ], 201);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $user->delete();
        } else {
            return response()->json(['error' => 'User not found']);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'User deleted',

        ], 200);
    }

    public function changePassword(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        if(Hash::check($request->old_password, $user->password)){
                $user->update(['password' => bcrypt($request->password)]);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Password changed'
            ]);
        }else{
             return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Old Password incorrect'
            ]);
        }
        
    }
}
