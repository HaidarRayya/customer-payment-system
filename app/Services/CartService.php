<?php

namespace App\Services;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartService
{
    /**
     * update a  cart
     * @param Cart $cart  
     *  @return CartResource cart
     */
    public function allCarts()
    {
        try {
            $carts = Cart::myCart(Auth::user()->id)->get();
            $carts = CartResource::collection($carts);
            return  $carts;
        } catch (Exception $e) {
            Log::error("error in get all carts"  . $e->getMessage());
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
     * create a  cart
     * @param Cart $cart  
     *  @return CartResource cart
     */
    public function createCart($cartData)
    {
        try {
            $cart = Cart::cart(Auth::user()->id, $cartData['product_id'])->first();
            if ($cart) {
                $cart->update(['count' => $cart->count + 1]);
            } else {
                $cart = Cart::create([
                    'customer_id' => Auth::user()->id,
                    'product_id' => $cartData['product_id'],
                    'count' => 1
                ]);
            }
            $cart = $cart->load('product');
            $cart = CartResource::make($cart);
            return $cart;
        } catch (Exception $e) {
            Log::error("error in create a product"  . $e->getMessage());
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
     * update a  cart
     * @param Cart $cart  
     *  @return CartResource cart
     */
    public function updateCart($cartData, $cart)
    {
        try {
            $cart->update($cartData);
            $cart = CartResource::make(Cart::find($cart->id)->load('product'));
            return $cart;
        } catch (Exception $e) {
            Log::error("error in update a  cart"  . $e->getMessage());
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
     * delete a  cart
     * @param Cart $cart  
     */
    public function deleteCart($cart)
    {
        try {
            $cart->delete();
        } catch (Exception $e) {
            Log::error("error in delete a  cart"  . $e->getMessage());
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
