<?php

namespace App\Services;

use App\Enums\PaymentSearch;
use App\Enums\UserRole;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\User;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * get all  payemnts
     * @param string $status
     * @param string $user_name
     * @return   PaymentResource $payemnts
     */
    public function allPayments($status, $user_name, $payment, $user_id)
    {
        try {
            if ($payment != null) {
                if ($user_id == null) {
                    $user = User::find(Auth::user()->id);
                } else {
                    $user = User::find($user_id);
                }
                switch ($payment) {
                    case PaymentSearch::LASTEST->value:
                        $payment = $user->load(['lastetPayment']);
                        break;
                    case PaymentSearch::OLDEST->value:
                        $payment = $user->load(['oldestPayment']);
                        break;
                    case PaymentSearch::HIGHEST->value:
                        $payment = $user->load(['highestPayment']);
                        break;
                    case PaymentSearch::LOWEST->value:
                        $payment = $user->load(['lowestPayment']);
                        break;
                }
                $payment = PaymentResource::make($payment);
                return $payment;
            }
            if (Auth::user()->role == UserRole::ADMIN->value) {
                $payemnts =  Payment::With('customer')
                    ->whereRelation('customer', 'name', 'like', "%$user_name%");
            } else if (Auth::user()->role == UserRole::CUSTOMER->value) {
                $payemnts = Payment::With('customer')
                    ->where('customer_id', '=', Auth::user()->id);
            }
            $payemnts = $payemnts
                ->byStatus($status)
                ->get();
            $payemnts = PaymentResource::collection($payemnts);
            return $payemnts;
        } catch (Exception $e) {
            Log::error("error in get all transfers" . $e->getMessage());
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
     * show  a  payment
     * @param Payment $payment 
     * @return   PaymentResource $payment 
     */
    public function onePayment($payment)
    {
        try {
            $payment = PaymentResource::make($payment);
            return  $payment;
        } catch (Exception $e) {
            Log::error("error in get a payment");
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
