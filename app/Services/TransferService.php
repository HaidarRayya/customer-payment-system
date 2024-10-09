<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Http\Resources\TransferResource;
use App\Models\Transfer;
use App\Models\User;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferService
{
    /**
     * get all  transfers
     * @param array $fillter
     * @param bool $deletedUsers
     * @return   TransferResource $transfers
     */
    public function allTransfers(array $fillter)
    {

        if (Auth::user()->role == UserRole::ADMIN->value) {
            if ($fillter['role'] == 'point reliter') {
                $transfers = Transfer::With('pointRelitier')
                    ->whereRelation('pointRelitier', 'name', 'like', "%$fillter[user_name]%");
            } else {
                $transfers = Transfer::With('customer')
                    ->whereRelation('customer', 'name', 'like', "%$fillter[user_name]%");
            }
        } else if (Auth::user()->role == UserRole::POINT_RELITIER->value) {
            $transfers = Transfer::With('customer')
                ->where('pointRelitier_id', '=', Auth::user()->id)
                ->whereRelation('customer', 'name', 'like', "%$fillter[user_name]%");
        }

        $transfers = $transfers
            ->byAmount($fillter['amount'])
            ->byDate($fillter['date'])
            ->bySort($fillter['sort'])
            ->get();
        $transfers = TransferResource::collection($transfers);
        return $transfers;
        try {
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
     * create  a  new transfer
     * @param array $data 
     */
    public function createTransfer($data)
    {
        try {
            $user = User::find(Auth::user()->id);
            $customer = User::byEmail($data['email'])->first();

            if ($user->role == UserRole::POINT_RELITIER->value) {
                DB::beginTransaction();
                $customer->update(['points' => $customer->points + $data['amount']]);
                if (!($user->update(['points' => $user->points - $data['amount']]))) {
                    DB::rollBack();
                } else {
                    DB::commit();
                }
            } else {
                $newPoints = $customer->points + $data['amount'];
                $customer->update(['points' => $newPoints]);
            }
            Transfer::create([
                'amount' => $data['amount'],
                'customer_id' => $customer->id,
                'pointRelitier_id' => $user->id,
            ]);
        } catch (Exception $e) {
            Log::error("error in create transfer" . $e->getMessage());
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
     * show  a  transfer
     * @param Transfer $transfer 
     * @return   TransferResource $transfer 
     */
    public function oneTransfer($transfer)
    {
        try {
            $transfer = TransferResource::make($transfer);
            return  $transfer;
        } catch (Exception $e) {
            Log::error("error in get a transfer");
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
