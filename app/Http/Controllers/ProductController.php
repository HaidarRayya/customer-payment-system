<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * show all products
     *
     * @param Request $request 
     *
     * @return response  of the status of operation : $products
     */
    public function index(Request $request)
    {
        $name = $request->input('name');
        $is_active = $request->input('is_active');
        $category_name = $request->input('category_name');

        $products = $this->productService->allProducts($name,  $is_active, $category_name, false);

        return response()->json([
            'status' => 'success',
            'data' => [
                'products' => $products
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     *  create a new  product
     *
     * @param StoreProductRequest $request 
     *
     * @return response  of the status of operation : the new book
     */
    public function store(StoreProductRequest $request)
    {
        $productData = $request->validated();
        $productimage = $request->file('image');

        $product = $this->productService->createProduct($productData, $productimage);

        return response()->json([
            'status' => 'success',
            'data' => [
                'product' => $product
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */

    /**
     *  show a specific  product
     *
     * @param Product $product 
     *
     * @return response  of the status of operation and product
     */
    public function show(Product $product)
    {
        $product = $this->productService->oneProduct($product);

        return response()->json([
            'status' => 'success',
            'data' => [
                'product' => $product
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     *  update a specific  product
     *
     * @param Product $product 
     * @param UpdateProductRequest $request 
     * @return response  of the status of operation : product
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $productData = $request->validated();
        $productimage = $request->file('image');
        $product = $this->productService->updateProduct($product,  $productData, $productimage);
        return response()->json([
            'status' => 'success',
            'data' => [
                'product' => $product
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     *  remove a specific  product
     *
     * @param Product $product 
     *
     * @return response  of the status of operation 
     */
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);
        return response()->json(status: 204);
    }


    /**
     * show all  deleted books
     *
     * @param Request $request 
     *
     * @return response  of the status of operation : and the books
     */
    public function deletedProducts(Request $request)
    {
        $name = $request->input('name');
        $is_active = $request->input('is_active');
        $category_name = $request->input('category_name');

        $products = $this->productService->allProducts($name, $is_active,  $category_name, true);

        return response()->json([
            'status' => 'success',
            'data' => [
                'products' => $products
            ]
        ], 200);
    }

    /**
     * restore a  product
     *
     * @param int $product_id 
     *
     * @return response  of the status of operation and the product
     */
    public function restoreProduct($product_id)
    {
        $product = $this->productService->restoreProduct($product_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'product' => $product
            ]
        ], 200);
    }
    /**
     * force delete a book
     * 
     * @param int $book_id 
     *
     * @return response  of the status of operation 
     */

    public function forceDeleteProduct($product_id)
    {
        $this->productService->forceDeleteProduct($product_id);
        return response()->json(status: 204);
    }
}