<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * get all  category
     * @param  Request $request  
     * @return response  of the status of operation and a categories
     */
    public function index(Request $request)
    {
        $name = $request->input('name');
        $categories = $this->categoryService->allCategories($name, false);
        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * create a  category
     * @param  StoreCategoryRequest $request  
     * @return response  of the status of operation and a category
     */
    public function store(StoreCategoryRequest $request)
    {
        $categoryData = $request->validated();

        $category = $this->categoryService->createCategory($categoryData);

        return response()->json([
            'status' => 'success',
            'data' => [
                'category' => $category,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * show a specific category
     * @param  Category $category  
     * @return response  of the status of operation and a category and all her books
     */
    public function show(Category $category)
    {
        $data = $this->categoryService->oneCategory($category);

        return response()->json([
            'status' => 'success',
            'data' => [
                'category' => $data['category'],
                'products' => $data['products']
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * update a specific category
     * @param UpdateCategoryRequest $request  
     * @param  Category $categoryData  
     * @return response  of the status of operation and a category
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $categoryData = $request->validated();
        $category = $this->categoryService->updateCategory($category,  $categoryData);
        return response()->json([
            'status' => 'success',
            'data' => [
                'category' => $category
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     *  delete a  specific category
     * @param Category $category  
     */
    public function destroy(Category $category)
    {
        $this->categoryService->deleteCategory($category);
        return response()->json(status: 204);
    }


    /**
     * show all  deleted categories
     *
     * @param Request $request 
     *
     * @return response  of the status of operation : and the categories
     */
    public function deletedCategory(Request $request)
    {
        $name = $request->input('name');

        $categories = $this->categoryService->allCategories($name, true);

        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories
            ]
        ], 200);
    }

    /**
     * restore a  category
     *
     * @param int $category_id 
     *
     * @return response  of the status of operation and the category
     */
    public function restoreCategory($category_id)
    {
        $category = $this->categoryService->restoreCategory($category_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'category' => $category
            ]
        ], 200);
    }
    /**
     * force delete a category
     * @param int $category_id 
     *
     * @return response  of the status of operation 
     */

    public function forceDeleteCategory($category_id)
    {
        $this->categoryService->forceDeleteCategory($category_id);
        return response()->json(status: 204);
    }
}
