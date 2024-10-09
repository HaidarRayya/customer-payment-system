<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transfer\FilterTransferRequest;
use App\Http\Requests\Transfer\StoreTransferRequest;
use App\Models\Transfer;
use App\Services\TransferService;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    protected $transferService;
    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(FilterTransferRequest $request)
    {
        $user_name = $request->input('user_name');
        $role = $request->input('role');
        $amount = $request->input('amount');
        $date = $request->input('date');
        $sort = $request->input('sort');

        $fillter = [
            'user_name' => $user_name,
            'role' => $role,
            'amount' => $amount,
            'date' => $date,
            'sort' => $sort
        ];
        $tranfers = $this->transferService->allTransfers($fillter);
        return response()->json([
            'status' => 'success',
            'data' => [
                'tranfers' =>  $tranfers
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransferRequest $request)
    {
        $transferData = $request->validated();
        $this->transferService->createTransfer($transferData);

        return response()->json([
            'status' => 'success',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transfer $transfer)
    {
        $transfer = $this->transferService->oneTransfer($transfer);

        return response()->json([
            'status' => 'success',
            'data' => [
                'transfer' => $transfer
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transfer $transfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transfer $transfer)
    {
        //
    }
}
