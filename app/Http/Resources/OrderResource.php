<?php

namespace App\Http\Resources;

use App\Enums\UserRole;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'price' => $this->price,
            'status' => $this->status,
            'customer_name' => $this->customer->name
        ];
    }
}
