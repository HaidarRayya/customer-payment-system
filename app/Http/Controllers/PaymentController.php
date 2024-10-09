<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\FilterPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(FilterPaymentRequest $request)
    {
        $status = $request->input('status');
        $user_name = $request->input('user_name');
        $payment = $request->input('payment');
        $user_id = $request->input('user_id');
        $payments = $this->paymentService->allPayments($status, $user_name, $payment, $user_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'payments' => $payments
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
    public function show(Payment $payment)
    {
        $payment = $this->paymentService->onePayment($payment);
        return response()->json([
            'status' => 'success',
            'data' => [
                'payment' => $payment
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
