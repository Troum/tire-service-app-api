<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
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
            'amount' => $this->amount,
            'user' => $this->whenLoaded('user', function () {
                return $this->user->name;
            }),
            'income' => $this->whenLoaded('info', function () {
                return $this->amount . ' x ' . $this->info->price . ' BYN = ' . $this->amount * $this->info->price;
            }),
            'producer' => $this->whenLoaded('info', function () {
                return $this->info->name;
            }),
            'season' => $this->whenLoaded('info', function () {
                return $this->info->type->season;
            }),
            'summary' => $this->whenLoaded('info', function () {
                return $this->amount * $this->info->price;
            }),
            'date' => $this->created_at
        ];
    }
}
