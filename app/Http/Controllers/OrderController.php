<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\AcceptOrderRequest;
use App\Http\Requests\Order\FilterOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * all orders
     * @return response  of the status of operation : orders
     */
    public function index(FilterOrderRequest $request)
    {
        $status = $request->input('status');
        $orders = $this->orderService->allOrders($status);

        return response()->json([
            'status' => 'success',
            'data' => [
                'orders' => $orders
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    /**
     * get a order
     * @param Order $order
     * @return response  of the status of operation : order
     */
    public function show(Order $order)
    {
        $data = $this->orderService->oneOrder($order);

        return response()->json([
            'status' => 'success',
            'data' => [
                'order' => $data['order'],
                'order_details' => $data['order_details']

            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * delete  a order
     * @param Order $order
     * @return response  of the status of operation 
     */
    public function destroy(Order $order)
    {
        Gate::authorize('delete-order', [Auth::user(), $order]);
        $this->orderService->deleteOrder($order);

        return response()->json(status: 204);
    }
    /**
     * confirm  a order
     * @return response  of the status of operation  : order
     */
    public function confirm()
    {
        $order = $this->orderService->confirmOrder();
        return response()->json([
            'status' => 'success',
            'data' => [
                'order' => $order
            ]
        ], 200);
    }
    /**
     * reject  a order
     * @param  Order $order
     * @return response  of the status of operation  : order
     */
    public function pays(Order $order)
    {
        $order = $this->orderService->paysOrder($order);
        return response()->json([
            'status' => 'success',
            'data' => [
                'order' => $order
            ]
        ], 200);
    }
    public function accept(Order $order)
    {
        $order = $this->orderService->acceptOrder($order);
        return response()->json([
            'status' => 'success',
            'data' => [
                'order' => $order
            ]
        ], 200);
    }
    /**
     * reject  a order
     * @param  Order $order
     * @return response  of the status of operation  : order
     */
    public function reject(Order $order)
    {
        $order = $this->orderService->rejectOrder($order);
        return response()->json([
            'status' => 'success',
            'data' => [
                'order' => $order
            ]
        ], 200);
    }
}