<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'unit_type'               => $this->unit_type,
            'unit_area'               => $this->unit_area . ' sqm',
            'unit_status'             => ucfirst($this->unit_status),
            'number_of_bedrooms'      => (int) $this->number_bedrooms,
            'number_of_bathrooms'     => (int) $this->number_bathrooms,
            'expected_delivery_date'  => $this->expected_delivery_date ? $this->expected_delivery_date->format('Y-m-d') : null,
            'created_at'              => $this->created_at->toDateTimeString(),
            'updated_at'              => $this->updated_at->toDateTimeString(),
            'deleted_at'              => $this->deleted_at,
            'user'                    => UserResource::make($this->whenLoaded('user')),
            'developer'               => DeveloperResource::make($this->whenLoaded('developer')),
            'location'                => LocationResource::make($this->whenLoaded('location')),
            'project'                 => ProjectResource::make($this->whenLoaded('project')),
            'media'                   => $this->getMedia('unit_photo')
        ];
    }
}
