<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];
        if (!$this->relationLoaded('customer')) {
            if ($this->relationLoaded('lastetPayment')) {
                $data['id'] = $this->lastetPayment->id;
                $data['amount'] = $this->lastetPayment->amount;
                $data['status'] = $this->lastetPayment->status;
                $data['date'] = Carbon::create($this->lastetPayment->updated_at)->format('Y-m-d');
            } else if ($this->relationLoaded('oldestPayment')) {
                $data['id'] = $this->oldestPayment->id;
                $data['amount'] = $this->oldestPayment->amount;
                $data['status'] = $this->oldestPayment->status;
                $data['date'] = Carbon::create($this->oldestPayment->updated_at)->format('Y-m-d');
            } else if ($this->relationLoaded('highestPayment')) {
                $data['id'] = $this->highestPayment->id;
                $data['amount'] = $this->highestPayment->amount;
                $data['status'] = $this->highestPayment->status;
                $data['date'] = Carbon::create($this->highestPayment->updated_at)->format('Y-m-d');
            } else if ($this->relationLoaded('lowestPayment')) {
                $data['id'] = $this->lowestPayment->id;
                $data['amount'] = $this->lowestPayment->amount;
                $data['status'] = $this->lowestPayment->status;
                $data['date'] = Carbon::create($this->lowestPayment->updated_at)->format('Y-m-d');
            }
            $data['customer_name'] = $this->name;
            return $data;
        }
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'status' => $this->status,
            'date' => Carbon::create($this->updated_at)->format('Y-m-d'),
            'customer_name' => $this->customer->name
        ];
    }
}