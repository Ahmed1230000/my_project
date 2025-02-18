<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeveloperResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'project_num' => $this->project_num,
            'unit_num'    => $this->unit_num,
            'phone_num'   => $this->phone_num,
            'address'     => $this->address,
            'user'        => UserResource::make($this->whenLoaded('user')),
            'units'       => UnitResource::collection($this->whenLoaded('units')),
            'created_at'  => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'  => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
