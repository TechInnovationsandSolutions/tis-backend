<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product as ResourcesProduct;
use App\Http\Resources\ProductCollection;
use App\Product;
use App\Services\FileUploadService;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ProductController extends Controller
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
            'message' => 'Products found',
            'data' => new ProductCollection(Product::with('category')->paginate(10)),
        ], 200);
    }

    public function store(ProductRequest $request)
    {
        // dd($request->file('images'));
        // return response()->json($request->file());
        $names = array();
        if ($images = $request->file()) {
            // dd($request->file('images'));

            //dd($images);

            foreach ($images as $image) {
                //dd($image);
                $img = $this->fileUploadService->uploadFile($image);
                $imageName = $img['secure_url'] ?? null;

                $names[] = $imageName;
            }

            $request->merge(['images' => json_encode($names)]);
        }

        $product = Product::create($request->except(['image']));

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Product created',
            'data' => new ResourcesProduct($product),
        ], 201);
    }

    public function show(Product $product)
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Product created',
            'data' => new ResourcesProduct($product),
        ], 200);
    }

    public function update(Product $product, ProductRequest $request)
    {
        $product->update($request->all());
        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Product updated',
            'data' => new ResourcesProduct($product),
        ], 201);
    }

    public function destroy($product)
    {
        $product = Product::findOrFail($product);
        if ($product) {
            $product->delete();
        } else {
            return response()->json(['error' => 'Product not found']);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Product deleted',

        ], 200);
    }
}
