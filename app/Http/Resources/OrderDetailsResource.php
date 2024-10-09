<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
            'image' => asset('storage/' .  $this->product->image),
            'count' => $this->count,
            'price' => $this->price,
            'title' => $this->product->name,
            'categoryName' => $this->product->category->name
        ];
    }
}
