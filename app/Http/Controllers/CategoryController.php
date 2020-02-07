<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\ProductCollection;
use App\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Categories',
            'data' => CategoryResource::collection(Category::all()),
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'picture' => 'required|image'
        ]);

        if ($images = $request->file()) {

            foreach ($images as $image) {
                //dd($image);
                $imageName = $image->store('categories', 'public');

                $request->merge(['image' => $imageName]);
            }
        }

        $category = Category::create($request->except('picture'));

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Category created',
            'data' => new CategoryResource($category),
        ], 201);
    }

    public function show(Category $id)
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Category',
            'data' => new CategoryResource($id),
        ], 200);
    }

    public function update(Category $id, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image'
        ]);

        if ($image = $request->file()) {

            $imageName = $image->store('categories', 'public');

            $request->merge(['image' => $imageName]);
        } else {
            $request->merge(['image' => $id->image]);
        }
        $id->update($request->all());
        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Category updated',
            'data' => new CategoryResource($id),
        ], 201);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category) {
            $category->delete();
        } else {
            return response()->json(['error' => 'Category not found']);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Product deleted',

        ], 200);
    }

    public function products($id)
    {
        Product::where('category_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Products found',
            'data' => new ProductCollection(Product::with('category')->paginate(10)),
        ], 200);
    }
}
