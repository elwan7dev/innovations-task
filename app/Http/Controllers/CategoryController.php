<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\services\CategoryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    private CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
        $this->middleware('abilities:force-list-categories');
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $categories = $this->service->getCategoriesWithConditions($request);
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'active' => $request->active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'item saved successfully...!',
            'data' => new CategoryResource($category)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category)
    {
        return response()->json([
            'success' => true,
            'message' => 'item retrieved successfully...!',
            'data' => new CategoryResource($category)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $isUpdated =$category->update([
            'name' => $request->name,
            'active' => $request->active,
        ]);

        return response()->json([
            'success' => (bool)$isUpdated,
            'message' => $isUpdated ? 'item updated successfully...!' : 'something wrong..!',
            'data' => new CategoryResource($category)
        ], $isUpdated ? 200 : 400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category)
    {
        $deleted = $category->delete();

        return response()->json([
            'success' => (bool) $deleted,
            'message' => $deleted ? 'item deleted successfully...!' : 'something wrong..!',
        ], $deleted ? 200 : 404);
    }
}
