<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * get all carts
     *    
     * @return response  of the status of operation : $carts
     */
    public function index()
    {

        $carts = $this->cartService->allCarts();

        return response()->json([
            'status' => 'success',
            'data' => [
                'carts' => $carts
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * create a cart
     * @param StoreCartRequest $request   
     * @return response  of the status of operation : $cart
     */
    public function store(StoreCartRequest $request)
    {

        $cartData = $request->validated();

        $cart = $this->cartService->createCart($cartData);

        return response()->json([
            'status' => 'success',
            'data' => [
                'cart' => $cart
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * update a cart
     * @param UpdateCartRequest $request   
     * @param Cart $cart  
     * @return response  of the status of operation : $cart
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        Gate::authorize('update-cart', [Auth::user(), $cart]);

        $cartData = $request->validated();

        $cart = $this->cartService->updateCart($cartData, $cart);
        return response()->json([
            'status' => 'success',
            'data' => [
                'cart' => $cart
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * delete a cart
     * @param Cart $cart  
     * @return response  of the status of operation
     */
    public function destroy(Cart $cart)
    {
        Gate::authorize('delete-cart', [Auth::user(), $cart]);

        $this->cartService->deleteCart($cart);
        return response()->json(status: 204);
    }
}
