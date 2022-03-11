<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreproductRequest;
use App\Http\Requests\UpdateproductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
        $this->middleware('abilities:force-list-products')->except('index');
        $this->middleware('ability:force-list-products,show-products-in-cart')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $products = $this->service->getProductsWithConditions($request);
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreproductRequest  $request
     * @return JsonResponse
     */
    public function store(StoreproductRequest $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'active' => $request->active,
            'description' => $request->active,
            'category_id' => $request->category,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'item saved successfully...!',
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'message' => 'item retrieved successfully...!',
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateproductRequest  $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(UpdateproductRequest $request, Product $product)
    {
        $isUpdated =$product->update([
            'name' => $request->name,
            'price' => $request->price,
            'active' => $request->active,
            'description' => $request->active,
            'category_id' => $request->category,
        ]);

        return response()->json([
            'success' => (bool)$isUpdated,
            'message' => $isUpdated ? 'item updated successfully...!' : 'something wrong..!',
            'data' => new ProductResource($product)
        ], $isUpdated ? 200 : 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product)
    {
        $deleted = $product->delete();

        return response()->json([
            'success' => (bool) $deleted,
            'message' => $deleted ? 'item deleted successfully...!' : 'something wrong..!',
        ], $deleted ? 200 : 404);
    }

}
