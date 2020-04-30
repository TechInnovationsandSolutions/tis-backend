<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Http\Resources\Cart as ResourcesCart;
use App\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart;
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'User Cart Contents',
            // 'data' => $cart,
            'data' => ResourcesCart::collection($cart),
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'amount' => 'required',
            'quantity' => 'required'
        ]);
        $cart = auth()->user()->cart()->updateOrCreate(
            [
                'product_id' => $request->product_id
            ],
            [
                'amount' => $request->amount,
                'quantity' => $request->quantity,
            ]
        );

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Item added to cart',
            // 'data' => $cart,
            'data' => new ResourcesCart($cart),
        ], 201);
    }

    public function show(Cart $id)
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Cart Item',
            'data' => new ResourcesCart($id),
        ], 200);
    }

    public function update(Request $request, Cart $id)
    {
        // dd($request->all());
        $id->update($request->validate([
            'product_id' => 'required',
            'amount' => 'required',
            'quantity' => 'required'
        ]));
        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Cart updated',
            'data' => new ResourcesCart($id),
        ], 201);
    }

    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        if ($cart) {
            $cart->delete();
        } else {
            return response()->json(['error' => 'Category not found']);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Item deleted from cart',

        ], 200);
    }
}
