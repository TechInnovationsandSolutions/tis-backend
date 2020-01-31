<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderAddress as ResourcesOrderAddress;
use App\OrderAddress;
use Illuminate\Http\Request;

class OrderAddressController extends Controller
{
    public function all()
    {
        $addresses = OrderAddress::all();
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'User Cart Contents',
            // 'data' => $cart,
            'data' => ResourcesOrderAddress::collection($addresses),
        ], 200);
    }

    public function index()
    {
        $addresses = auth()->user()->addresses;
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'User Addresses',
            // 'data' => $cart,
            'data' => ResourcesOrderAddress::collection($addresses),
        ], 200);
    }

    public function store(Request $request)
    {
        $addresses = auth()->user()->addresses()->create($request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'state_id' => 'required',
            'lga_id' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ]));

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Address created',
            // 'data' => $cart,
            'data' => new ResourcesOrderAddress($addresses),
        ], 201);
    }

    public function show(OrderAddress $id)
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Address',
            'data' => new ResourcesOrderAddress($id),
        ], 200);
    }

    public function update(Request $request, OrderAddress $id)
    {
        // dd($request->all());
        $id->update($request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'state_id' => 'required',
            'lga_id' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ]));
        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Address updated',
            'data' => new ResourcesOrderAddress($id),
        ], 201);
    }

    public function destroy($id)
    {
        $cart = OrderAddress::findOrFail($id);
        if ($cart) {
            $cart->delete();
        } else {
            return response()->json(['error' => 'Address not found']);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Address deleted',

        ], 200);
    }
}
