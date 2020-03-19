<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\ProductCollection;
use App\Product;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
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
            'picture' => 'required'
        ]);



        $request->merge(['image' => $request->picture['url'], 'thumbnail' => $request->picture['thumbnail']]);

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
            'picture' => 'nullable|image'
        ]);


        if ($request->has('picture')) {

             $request->merge(['image' => $request->picture['url'], 'thumbnail' => $request->picture['thumbnail']]);
        }
        $id->update($request->except(['picture']));
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
        $products = Product::with('category')->where('category_id', $id)->paginate(10);
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Products found',
            'data' => new ProductCollection($products),
        ], 200);
    }
}
