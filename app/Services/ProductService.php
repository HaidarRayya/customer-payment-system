<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * show all  products
     * @param string $name   
     * @param bool $is_active  
     * @param bool $deletedProducts 
     * @return ProductResource products 
     */
    public function allProducts($name, $is_active, $category_name, $deletedProducts)
    {

        try {
            if ($deletedProducts) {
                $products = Product::onlyTrashed()
                    ->With('category')
                    ->whereRelation('category', 'name', 'like', "%$category_name%");
            } else {
                $products = Product::With('category')
                    ->whereRelation('category', 'name', 'like', "%$category_name%");
            }
            $products = $products
                ->byName($name)
                ->byIsActive($is_active)
                ->get();
            $products = ProductResource::collection($products);
            return $products;
        } catch (Exception $e) {
            Log::error("error in get all products"  . $e->getMessage());
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
     * show a specific product
     * @param  $product  
     * @return ProductResource product  
     */
    public function oneProduct($product)
    {

        try {
            $product->load('category');
            $product = ProductResource::make($product);
            return $product;
        } catch (Exception $e) {
            Log::error("error in  show a  product"  . $e->getMessage());
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
     * create a  new product
     * @param  $request  
     * @return ProductResource product  
     */
    public function createProduct($productData, $productimage)
    {

        try {
            if ($productimage != null) {
                $productData['image'] = $productimage->store('imagesProducts', 'public');
            }
            $product = Product::create($productData);
            $product  = ProductResource::make($product);
            return  $product;
        } catch (Exception $e) {
            Log::error("error in create a  product"  . $e->getMessage());
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
     * update a specific product
     * @param Product $product  
     * @param  $request  
     * @return ProductResource product  
     */
    public function updateProduct($product, $productData, $productimage)
    {
        try {
            if ($productimage != null) {
                Storage::delete($product->image);
                $productData['image'] = $productimage->store('imagesBooks', 'public');
            }
            $product->update($productData);
            $product = ProductResource::make(Product::find($product->id));
            return  $product;
        } catch (Exception $e) {
            Log::error("error in   update a  product"  . $e->getMessage());
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
     * soft delete a specific  product
     * @param Product $product  
     */
    public function deleteProduct(Product $product)
    {
        try {
            $product->delete();
        } catch (Exception $e) {
            Log::error("error in  soft delete a  product"  . $e->getMessage());
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
     * restore a specific product
     * @param int $product_id      
     * @return ProductResource $product
     */
    public function restoreProduct($product_id)
    {
        try {
            $product = Product::withTrashed()->find($product_id);
            $product->restore();
            return ProductResource::make($product);
        } catch (Exception $e) {
            Log::error("error in restore a product"  . $e->getMessage());
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
     * delete a specific product
     * @param Product $product  
     */
    public function forceDeleteProduct($product_id)
    {
        try {
            $product = Product::withTrashed()->find($product_id);
            $product->forceDelete();
        } catch (Exception $e) {
            Log::error("error in delete a  product"  . $e->getMessage());
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "there is something wrong in server",
                ],
                500
            ));
        }
    }
}