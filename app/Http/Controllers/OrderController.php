<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Http\Resources\Order as OrderResource;
use App\Order;
use App\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function all()
    {
        $orders = Order::with('user', 'address')->get();
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'All Orders Made',
            // 'data' => $cart,
            'data' => OrderResource::collection($orders),
        ], 200);
    }
    public function index()
    {
        $cart = auth()->user()->order;
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'User\'s Order',
            // 'data' => $cart,
            'data' => OrderResource::collection($cart),
        ], 200);
    }

    public function store(Request $request)
    {

        $request->validate([
            'address_id' => 'required'
        ]);

        $cart = auth()->user()->cart;

        $order = 0;

        if (count($cart)) {

            $amount = 0;
            foreach ($cart as $item) {
                $amount += $item->quantity * $item->amount;
                $products[] = ['id' => $item->product_id, 'amount' => $item->amount, 'quantity' => $item->quantity];
            }

            //dd($products);

            $order = auth()->user()->orders()->create(
                [
                    'products' => json_encode($products),
                    'address_id' => $request->address_id,
                    'amount' => $amount,
                    'quantity' => 0,
                    'status' => 0,
                ]
            );

            Cart::whereIn('id', $cart->pluck('id'))->delete();
        }
        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => $order ? 'Order created' : 'No order',
            // 'data' => $cart,
            'data' => $order ? new OrderResource($order) : 'No Items in cart',
        ], 201);
    }

    public function show(Order $id)
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Order Details',
            'data' => new OrderResource($id),
        ], 200);
    }

    public function update(Request $request, Order $id)
    {
        // dd($request->all());
        $request->validate([
            'address_id' => 'required'
        ]);

        $cart = auth()->user()->cart;

        $order = 0;

        dd($id->products);
        if (count($cart)) {

            $amount = 0;
            foreach ($cart as $item) {
                $amount += $item->quantity * $item->amount;
                $products[] = ['id' => $item->product_id, 'amount' => $item->amount, 'quantity' => $item->quantity];
            }

            //dd($products);

            $amount = $id->amount + $amount;
            $sum = $products + $id->products;

            $id->update(
                [
                    'products' => json_encode($sum),
                    'address_id' => $request->address_id,
                    'amount' => $amount,
                    'quantity' => 0,
                    'status' => 0,
                ]
            );

            Cart::whereIn('id', $cart->pluck('id'))->delete();
        }

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Order updated',
            'data' => new OrderResource($id),
        ], 201);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order) {
            $order->delete();
        } else {
            return response()->json(['error' => 'Category not found']);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Order deleted',

        ], 200);
    }

    public function userOrders(User $user)
    {
        $cart = $user()->order;
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'User\'s Orders',
            // 'data' => $cart,
            'data' => OrderResource::collection($cart),
        ], 200);
    }
}
