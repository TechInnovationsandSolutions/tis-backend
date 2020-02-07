<?php

namespace App\Http\Controllers;

use App\Http\Resources\Rating as ResourcesRating;
use App\Product;
use App\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function all()
    {
        $ratings = Rating::all();
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'All Product Ratings',
            'data' => ResourcesRating::collection($ratings),
        ], 200);
    }

    public function index(Product $product)
    {
        $ratings = $product->ratings;

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Product Ratings',
            // 'data' => $cart,
            'data' => ResourcesRating::collection($ratings),
        ], 200);
    }

    public function store(Request $request, Product $product)
    {
        $ratings = $product->ratings()->create($request->validate([
            'name' => 'required',
            'rate' => 'required',
            'comment' => 'required',
        ]));

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Rating stored',
            // 'data' => $cart,
            'data' => new ResourcesRating($ratings),
        ], 201);
    }

    public function show(Rating $id)
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Cart Item',
            'data' => new ResourcesRating($id),
        ], 200);
    }

    public function update(Request $request, Rating $id)
    {
        $id->update($request->validate([
            'name' => 'required',
            'rate' => 'required',
            'comment' => 'required',
        ]));
        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Rating updated',
            'data' => new ResourcesRating($id),
        ], 201);
    }

    public function destroy($id)
    {
        $cart = Rating::findOrFail($id);
        if ($cart) {
            $cart->delete();
        } else {
            return response()->json(['error' => 'Rating not found']);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Rating deleted',

        ], 200);
    }
}
