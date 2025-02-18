<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
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
            'city'           => $this->city,
            'new attribute'  => $this->new_attribute,
            'neighborhood'   => $this->neighborhood,
            'lat'            => $this->lat,
            'lon'            => $this->lon,
            'user'           => UserResource::make($this->whenLoaded('user')),
            'units'          => UnitResource::collection($this->whenLoaded('units')),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'deleted_at'     => $this->deleted_at,
        ];
    }
}
