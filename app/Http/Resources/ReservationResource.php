<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'payment_method' => $this->payment_method,
            'down_payment'   => $this->down_payment,
            'payment_date'   => $this->payment_date ? $this->payment_date : 'N/A',
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'user'           => UserResource::make($this->whenLoaded('user')), // Include user relationship if loaded
            'unit'           => UnitResource::make($this->whenLoaded('unit')), // Include unit relationship if loaded
        ];
    }
}
