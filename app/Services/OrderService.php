<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    /**
     * show all  orders
     * @return OrderResource $orders 
     */
    public function allOrders($status)
    {
        try {

            $orders = Order::with('customer')
                ->byStatus($status)
                ->get();
            $orders = OrderResource::collection($orders);
            return $orders;
        } catch (Exception $e) {
            Log::error("error in get all orders"  . $e->getMessage());
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
     * get one order and all  $order_details 
     * @return array  OrderResource $order and  OrderDetailsResource $order_details 
     */
    public function oneOrder($order)
    {
        try {
            $order = $order->load(['order_details.product', 'customer']);
            $order_details = OrderDetailsResource::collection($order->order_details);
            $order = OrderResource::make($order);
            return [
                'order' => $order,
                'order_details' => $order_details
            ];
        } catch (Exception $e) {
            Log::error("error in  show a order"  . $e->getMessage());
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
     * confirm order 
     * @return OrderResource order  
     */
    public function confirmOrder()
    {
        $carts = Cart::myCart(Auth::user()->id)->with('product')->get();
        if ($carts->isEmpty()) {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "لا يوجد لديك منتجات لشراءها",
                ],
                422
            ));
        } else {
            $orderPrice = 0;
            foreach ($carts as $cart) {
                $product = $cart->product;
                if ($product->count < $cart->count) {
                    $message = "لا يمكنك شراء هذه الكمية من المنتج " . $product->name . "الكمية المتوفرة هي " . $product->count;
                    throw new HttpResponseException(response()->json(
                        [
                            'status' => 'error',
                            'message' => $message,
                        ],
                        422
                    ));
                } else {
                    $orderPrice += ($product->price * $cart->count);
                }
            }
            try {
                $order = Order::create([
                    'customer_id' => Auth::user()->id,
                    'price' => $orderPrice,
                ]);
                foreach ($carts as $cart) {
                    OrderDetails::create([
                        'order_id' => $order->id,
                        'count' => $cart->count,
                        'price' => $cart->product->price,
                        'product_id'  => $cart->product_id,
                    ]);
                    $product = Product::find($cart->product->id);
                    $product->update([
                        'count' => $product->count - $cart->count,
                    ]);
                    $cart->delete();
                }

                Payment::create([
                    'amount' => $order->price,
                    'order_id' => $order->id,
                    'customer_id' => Auth::user()->id
                ]);
                $order = OrderResource::make($order);
                return $order;
            } catch (Exception $e) {
                Log::error("error in confirm a order"  . $e->getMessage());
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

    public function deleteOrder($order)
    {

        try {
            $order_details = OrderDetails::where('order_id', '=', $order->id)->get();
            foreach ($order_details as $o) {
                $product = Product::find($o->product_id);
                $product->update([
                    'count' => $product->count + $o->count,
                ]);
                $o->delete();
            }
            $order->delete();
        } catch (Exception $e) {
            Log::error("error in delete a  order"  . $e->getMessage());
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
     * reject order 
     * @param Order $order  
     * @return OrderResource order  
     */
    public function paysOrder(Order $order)
    {
        if ($order->status != OrderStatus::UNPAID->value) {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "لا يمكنك دفع هذه الطلبية لقد تمت معالجتها سابقا",
                ],
                422
            ));
        }
        $customer = User::find(Auth::user()->id);
        if ($customer->points < $order->price) {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "لا يمكنك دفع هذه الطلبية ليس لديك رصيد كافي",
                ],
                422
            ));
        }
        try {
            $customer->update([
                'points' =>  $customer->points - $order->price
            ]);
            $order->status = OrderStatus::PAID->value;
            $order->save();

            $payment = Payment::myPayment(Auth::user()->id, $order->id)->first();
            $payment->status = PaymentStatus::PAID->value;
            $payment->save();
            $order = OrderResource::make($order);
            return $order;
        } catch (Exception $e) {
            Log::error("error in  soft delete a  role"  . $e->getMessage());
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
     * accept order 
     * @param Order $order  
     * @param  array $orderData  
     * @return OrderResource order  
     */
    public function acceptOrder(Order $order)
    {

        if ($order->status != OrderStatus::PAID->value) {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "لا يمكنك موافقة على هذه الطلبية لم يتم دفعها بعد",
                ],
                422
            ));
        }
        try {
            $order->status = OrderStatus::ACCEPTED->value;
            $order->save();
            $order->load('customer');
            $order = OrderResource::make($order);

            return $order;
        } catch (Exception $e) {
            Log::error("error in accept order"  . $e->getMessage());
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
     * reject order 
     * @param Order $order  
     * @return OrderResource order  
     */
    public function rejectOrder(Order $order)
    {
        if ($order->status == OrderStatus::ACCEPTED->value) {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "لا يمكنك رفض هذه الطلبية لقد تمت معالجتها سابقا",
                ],
                422
            ));
        }

        try {
            $order->status = OrderStatus::REJECTED->value;
            $order->save();
            $order_details = OrderDetails::where('order_id', '=', $order->id)->get();
            foreach ($order_details as $o) {
                $product = Product::find($o->product_id);
                $product->update([
                    'count' => $product->count + $o->count,
                ]);
                $o->delete();
            }
            $order = OrderResource::make($order);
            return $order;
        } catch (Exception $e) {
            Log::error("error in  soft delete a  role"  . $e->getMessage());
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