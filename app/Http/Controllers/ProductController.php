<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product as ResourcesProduct;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\Tag;
use App\Product;
use App\Services\FileUploadService;
use App\Tag as AppTag;
use App\User;
use Illuminate\Http\Request;


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
            'data' => new ProductCollection(Product::with('category', 'images', 'tags')->paginate(20)),
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

        if ($request->has('tags') && $request->tags != '') {
            $product->attachTag($request->tags);
        }

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Product created',
            'data' => new ResourcesProduct($product),
        ], 201);
    }

    public function show(Product $product)
    {
        // dd($product->images());
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

    public function search(Request $request)
    {
        $products = Product::with('category', 'images', 'tags')->where('name', 'LIKE', '%' . $request->search . '%')->paginate(20);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Products found',
            'data' => new ProductCollection($products),
        ], 200);
    }

    public function findByTag(Request $request)
    {
        $tag = Product::withAnyTags([$request->tag])->paginate(15);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => count($tag) ? 'Products found' : 'no products found',
            'data' => count($tag) ? new ProductCollection($tag) : '',
        ], 200);
    }

    public function test()
    {
        dd('hi');
    }
}
