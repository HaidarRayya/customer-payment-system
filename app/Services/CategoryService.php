<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Exceptions\HttpResponseException;

class  CategoryService
{
    /**
     * show all  categories
     * @param string $name  
     * @return CategoryResource $categories 
     */
    public function allCategories($name, $deletedCategory)
    {
        try {

        if ($deletedCategory) {
            $categories = Category::onlyTrashed();
        } else {
            $categories = Category::query();
        }
        $categories = $categories
            ->byName($name)
            ->get();
        $categories = CategoryResource::collection($categories);
        return  $categories;
        } catch (Exception $e) {
            Log::error("error in get all categories"  . $e->getMessage());
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "there is something wrong in server",
                ],
                500
            ));
        }
    }
    /**
     * show a category and all  her products
     * @param  Category $category  
     * @return array CategoryResource $category and ProductResource $products
     */
    public function oneCategory($category)
    {

        try {
            $products = $category->load('products')->products;
            $category = CategoryResource::make($category);

            $products = $products->isNotEmpty() ? ProductResource::collection($products) : [];

            return [
                'category' => $category,
                'products' =>  $products
            ];
        } catch (Exception $e) {
            Log::error("error in  show a  category"  . $e->getMessage());
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "there is something wrong in server",
                ],
                500
            ));
        }
    }
    /**
     * create a  new category
     * @param  array $CategoryData  
     * @return CategoryResource book  
     */
    public function createCategory($categoryData)
    {

        try {
            $category = Category::create($categoryData);
            $category  = CategoryResource::make($category);
            return  $category;
        } catch (Exception $e) {
            Log::error("error in create a  category"  . $e->getMessage());
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "there is something wrong in server",
                ],
                500
            ));
        }
    }
    /**
     * update a category
     * @param Category $category  
     * @param  array $categoryData  
     * @return CategoryResource category  
     */
    public function updateCategory(Category $category, $categoryData)
    {
        try {
            $category->update($categoryData);
            $category = CategoryResource::make(Category::find($category->id));
            return  $category;
        } catch (Exception $e) {
            Log::error("error in   update a  category"  . $e->getMessage());
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "there is something wrong in server",
                ],
                500
            ));
        }
    }

    /**
     *  delete a  category
     * @param Category $category  
     */
    public function deleteCategory(Category $category)
    {
        try {
            $category->delete();
        } catch (Exception $e) {
            Log::error("error in  soft delete a  category"  . $e->getMessage());
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "there is something wrong in server",
                ],
                500
            ));
        }
    }



    /**
     * restore a book
     * @param int $category_id      
     * @return CategoryResource $category
     */
    public function restoreCategory($category_id)
    {
        try {
            $category = Category::withTrashed()->find($category_id);
            $category->restore();
            return CategoryResource::make($category);
        } catch (Exception $e) {
            Log::error("error in restore a category"  . $e->getMessage());
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "there is something wrong in server",
                ],
                500
            ));
        }
    }

    /**
     * delete a  book
     * @param Category $category  
     */
    public function forceDeleteCategory($category_id)
    {
        try {
            $category = Category::withTrashed()->find($category_id);
            $category->forceDelete();
        } catch (Exception $e) {
            Log::error("error in delete a  category"  . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
}