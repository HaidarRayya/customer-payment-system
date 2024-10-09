<?php

namespace App\Http\Resources;

use App\Enums\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];
        if (Auth::user()->role == UserRole::ADMIN->value) {
            $data['pointRelitierName'] = $this->pointRelitier->name ?? '';
            $data['customerName'] = $this->customer->name ?? '';
            $data['email'] = $this->customer->email ?? '';
        } else if (Auth::user()->role == UserRole::POINT_RELITIER->value) {
            $data['customerName'] = $this->customer->name ?? '';
            $data['email'] = $this->customer->email ?? '';
        }
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'date' => Carbon::create($this->created_at)->format('Y-m-d'),
            ...$data
        ];
    }
}
